<?php include 'common/header.php'; ?>
<a href="<?php echo $params['addWordsUrl'] ?>"><button>Add Words</button></a>
<table class="table">
    <thead>
        <tr>
            <th>Word</th>
            <th>Frequency</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($params['words'] as $record) { ?>
        <tr>
            <th>
                <a href="<?php echo $params['viewWordUrl'] ?>?wordId=<?php echo $record->id ?>">
                    <?php echo $record->word ?>
                </a>
            </th>
            <td><?php echo $record->frequency ?></td>
        </tr>
    <?php } ?>
    </tbody>
    <tfoot>
        <th>Current Page: <?php echo $params['currPage']; ?> / of / <?php echo $params['pagesCount']; ?></th>
        <td>
            <form>
                <input type="int" name="page">
                <input type="submit" value="Go To>>">
            </form>
        </td>
    </tfoot>
</table>

<?php include 'common/footer.php'; ?>
