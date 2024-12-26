<section class="content-header">
    <div class="row">
      <div class="col-md-12">
            <ol class="breadcrumb pull-right">
                <li>
                    <a href="<?php echo base_url('homepage'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
                </li>
                <li>
                  <a href="<?php echo base_url($controller_link); ?>"> <?php echo $controller_name; ?></a>
                </li>
                <li class="active">Print attachment</li>
            </ol>
      </div> 
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="pull pull-right">
                <button onclick="printDiv('print-section')" class="btn btn-default btn-sm pull-right "><i class="fa fa-print pull-left"></i> Print Report
                </button>
            </div>
        </div>        
    </div> 
    <hr />
</section>
<section class="content" id="print-section">
    <div class="invoice invoice-body">
        <div class="row text-center">
           <img class="img-preview" src="<?php echo base_url().$img_path; ?>" />
        </div>
    </div>
</section>