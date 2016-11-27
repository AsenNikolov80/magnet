<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 27.11.2016 г.
 * Time: 12:02
 */
?>
<script>
    if ($('#user-map_link').val().length > 0) {
        $('#edit-user').submit();
        $(this).dialog("close");
    } else {
        $('#warn').remove();
        $('#user-map_link').parent().parent().css('border', '2px dashed lightcoral');
        $('#edit-user').append('<span id="warn" style="color: red;font-size: 1.5em">Линк към карта е задължителен</span>')
    }
</script>
