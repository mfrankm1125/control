<?php 
$dir = __DIR__ . DIRECTORY_SEPARATOR . 'csv';
$fileCount = 0;
$daemonTimer = 100000;
//read the config file
$config = parse_ini_file( 'config.ini', true);
$os = strtoupper($config['soffice']['os']);
$path = $config['soffice']['path'];
$auto = $config['soffice']['auto'];
$refresh = $config['soffice']['refresh'];
$innerCounter = 0;
$pathArray = explode(DIRECTORY_SEPARATOR, $path);
$dirs = count($pathArray);


$invisible = ' --headless';
$invisible .= ' --nolockcheck';
$invisible .= ' --nodefault';
$invisible .= ' --nologo';
$invisible .= ' --norestore';
$invisible .= ' --nofirststartwizard';

if ($os == "WINDOWS") {
    //build the command
    $base = 'cd \\ && ';
    for ($j = 1; $j < $dirs - 1; $j++) {
        $base .= 'cd "' . $pathArray[$j] . '" ';
        $base .= '&& ';
    }
    //if we do not use the start parameter we may crash libreoffice
    //due to a known bug
    $cli = $base . 'start ' . $pathArray[$dirs-1] . $invisible;
    if ($auto) {
        $inicio = microtime(true);
        exec('taskkill /fi "imagename eq soffice*" /f', $kill);
        exec('start /b soffice.vbs "' . $path . '"', $start);
        unset($kill);
        unset($start);
        $completed = microtime(true);
        //echo 'started in: ' . $inicio .PHP_EOL;
        //echo 'completed in: ' . $completed .PHP_EOL;
    }
} else {
    //build the command
    $cli = 'cd ';
    for ($j = 1; $j < $dirs - 1; $j++) {
        $cli .= DIRECTORY_SEPARATOR . $pathArray[$j];
    }
    $cli .= ' & ' . $pathArray[$dirs-1];
    if ($auto) {
        $inicio = microtime(true);
        exec("kill -9 `pgrep -f soffice`", $kill);
        exec('nohup ' . $path . ' --norestore > /dev/null &', $start);
        unset($kill);
        unset($start);
        $completed = microtime(true);
        //echo 'started in: ' . $inicio .PHP_EOL;
        //echo 'completed in: ' . $completed .PHP_EOL;
    }
    $cli .= $invisible;
}

while(true) {
    usleep($daemonTimer);
    $files = glob($dir . '/*.csv');
    $newCount = count($files);
    if ($newCount > 0 && $newCount >= $fileCount) {
        if ($auto){
            $innerCounter++;
            if ($innerCounter > $refresh && $auto) {
                if ($os == "WINDOWS") {
                    //we restart soffice to keep low the  memory consumption
                    exec('taskkill /fi "imagename eq soffice*" /f', $kill);
                    exec('start /b soffice.vbs "' . $path . '"', $start);
                    unset($kill);
                    unset($start);
                } else {
                    exec("kill -9 `pgrep -f soffice`", $kill);
                    exec('nohup ' . $path . ' --norestore > /dev/null &', $start);
                    unset($kill);
                    unset($start);
                }
                $innerCounter = 0;
            }
        }
        $rendered = renderDoc($files[0], $cli, $os);
        //check that the file exists and have not been removed by other process
        //in the meantime
        $exist = file_exists($files[0]);
        if ($exist) {
            $unlink = unlink($files[0]);
            if ($unlink === false) {
                //If there is a problem unlinking files so we should try again
                //and leave because otherwise the script may crash the server
                usleep(100000);
                $exist = file_exists($files[0]);
                $unlink = unlink($files[0]);
                if ( $exist && !$unlink) {
                    //write to the log explaining the problem and exit
                    echo "The csv folder is not writable.";
                    exit();
                }
            }
        }
        $fileCount = $newCount -1;
    }
}

function renderDoc($file, $cli, $os) {
    //let it breath to avoid conflicts
    usleep(1000);
    //open file and read command
    $tmp = fopen($file, 'r');
    $data = fread($tmp, 1000);
    fclose($tmp);
    $info = explode('|', $data);
    $path = $info[0];
    $target = $info[1];
    $ext = $info[2];
    if ($ext == 'ODT' || ($ext == 'DOC' && $info[3] == 'false')) {
        //this is a hack to update TOCs in (not legacy) .doc format 
        //and handle legacy DOC in LibreOffice5
        $info[2] = 'ODT';
        //if the target file is odt or (not legacy) .doc we have to rename
        //the original odt file that is later removed
        $regex ='/(.odt)$/';
        $info[1] = preg_replace($regex, '_new.odt', $info[1]);
        $data = implode('|', $info);
    }
    
    $exists = file_exists($path);
    $init = microtime(true);
    if (!empty($data) && $exists) {
        $cmd = $cli . ' "macro:///Docxpresso.Docxpresso.Render(' . $data . ')"';
        exec($cmd, $res);
        unset($res);
    } 

    //rename the generated files
    if (strpos($target, '_h5p_')){
        $remove = true;
    } else {
        $remove = false;
    }
    $newPath = preg_replace('/(_h5p_[a-z0-9]*)\./', '.', $target);
    if ($ext == 'ODT' || ($ext == 'DOC' && $info[3] == 'false')) {
        if ($remove) {
            unlink($path);
        }
        if ($ext == 'DOC') {
            $info[1] = preg_replace('/(_h5p_[a-z0-9]*)\.doc/', 
                                    '$1_new.odt', 
                                    $info[1]);
        }
        $new = file_exists($info[1]);
        if ($new) {
            rename($info[1],  $newPath);
        } else {
            $nameArray = explode('.', $info[0]);
            $oldext = array_pop($nameArray);
            $searchName = implode('.', $nameArray) . '_new.odt';
            rename($searchName,  $newPath);
        }
    } else if ($ext == 'DOC' && $info[3] == 'true'){
        if ($remove) {
            unlink($path);
        }
        $new = file_exists($target);
        if ($new) {
            rename($target,  $newPath);
        }
    } else {
        if ($remove) {
            unlink($path);
        }
        $new = file_exists($target);
        if ($new) {
            rename($target,  $newPath);
        }
    }
    $end = microtime(true);
    //Uncomment next line if you wish to echo the time taken for the conversion
    //echo 'conversion_time:' . ($end - $init) . PHP_EOL;
    //back to the infinite loop
}