<?php if(Session::has('error')): ?>
    <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-ban"></i> Error!</h4> <?php echo e(session::get('error')); ?>

    </div>
<?php endif; ?>

<?php if(Session::has('success')): ?>
    <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Success!</h4>   <?php echo e(session::get('success')); ?>

            
    </div>
<?php endif; ?><?php /**PATH /home/mangal/Ecommerce_website/resources/views/admin/message.blade.php ENDPATH**/ ?>