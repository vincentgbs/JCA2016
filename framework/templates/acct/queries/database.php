<tr>
    <td>
        <?php echo $data->nickname; ?>
    </td>
    <td>
        <?php echo $data->host; ?>
    </td>
    <td>
        <?php echo $data->username; ?>
    </td>
    <td>
        <?php echo $data->password; ?>
    </td>
    <td>
        <?php echo $data->database; ?>
    </td>
    <td>
        <button class="btn btn-warning delete_database_button" dbid="<?php echo $data->db_id; ?>">
            Delete
        </button>
    </td>
</tr>
