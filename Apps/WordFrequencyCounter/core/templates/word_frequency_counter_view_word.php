<?php include 'common/header.php'; ?>
<a href="<?php echo $params['goBackUrl'] ?>"><button>Go Back To Listing</button></a>
<table class="table">
    <thead>
        <tr>
            <th>Word</th>
            <th>Frequency</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($params['errorMsg']) { ?>
            <tr>
                <th colspan="2"><?php echo $params['errorMsg'] ?></th>
            </tr>
        <?php } else { ?>
            <tr>
                <th><?php echo $params['word']->word ?></th>
                <td><?php echo $params['word']->frequency ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include 'common/footer.php'; ?>
