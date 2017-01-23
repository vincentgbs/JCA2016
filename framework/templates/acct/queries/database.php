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
        <input type="password" value="<?php echo $data->password; ?>" disabled>
    </td>
    <td>
        <?php echo $data->database; ?>
    </td>
    <td>
        <button class="btn btn-success select_database_button" dbid="<?php echo $data->db_id; ?>" <?php echo (NULL); ?>>
            Select
        </button>
    </td>
    <td>
        <button class="btn btn-warning delete_database_button" dbid="<?php echo $data->db_id; ?>">
            Delete
        </button>
    </td>
</tr>
