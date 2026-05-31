<?php include 'common/header.php'; ?>

<a href="<?php echo $params['goBackUrl'] ?>"><button>Go Back To Listing</button></a>
<div>
    <div><p style="font-weight: bold;"><?php echo $params['successMsg'] ?></p></div>
    <div><p style="font-weight: bold;"><?php echo $params['errorMsg'] ?></p></div>
    <form action="<?php echo $params['submitUrl'] ?>" method="post">
        <div>
            <textarea name="words" cols="30" rows="20" style="min-width: 50%; max-width: 100%;"></textarea>
        </div>
        <div style="margin: 10px 0; font-weight: bold;">
            <small>*NOTE: Only Alphabet And Digits Are Allowed</small>
        </div>
        <div>
            <button type="submit">Submit</button>
        </div>
    </form>
</div>

<?php include 'common/footer.php'; ?>

