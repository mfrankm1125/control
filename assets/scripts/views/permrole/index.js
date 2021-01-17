/**
 * Created by Frank on 08/11/2016.
 */
$(function()	{
    // Chosen
     
    // Draggable Multiselect
    $('#btnSelect').click(function()	{

        $('#selectedBox1 option:selected').appendTo('#selectedBox2');
        return false;
    });

    $('#btnRemove').click(function()	{
        $('#selectedBox2 option:selected').appendTo('#selectedBox1');
        return false;
    });

    $('#btnSelectAll').click(function()	{

        $('#selectedBox1 option').each(function() {
            $(this).appendTo('#selectedBox2');
        });

        return false;
    });

    $('#btnRemoveAll').click(function()	{

        $('#selectedBox2 option').each(function() {
            $(this).appendTo('#selectedBox1');
        });

        return false;
    });
});
