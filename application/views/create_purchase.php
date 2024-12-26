<section class="content">
    <div class="box" id="print-section">
        <div class="box-header">
            <h3 class="box-title purchase-top-header"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Create Purchase
            </h3>
            <div class="pull pull-right purchase-top-header">
                <a type="button" class="btn btn-danger btn-flat btn-sm" href="<?php echo base_url('purchase');?>" ><i class="fa fa-times" aria-hidden="true"></i> Cancel Purchase
                </a>
            </div>
        </div>
        <div class="box-body ">
                <?php
                    $attributes = array('id'=>'create_purchase_form','method'=>'post','class'=>'');
                ?>
                <?php echo form_open_multipart('purchase/add_purchase',$attributes); ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="purchase-heading"><i class="fa fa-check-circle"></i> General Detail :</h4>              
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <?php echo form_label('Supplier'); ?>
                                <select name="pur_supplier" class="form-control input-lg">
                                    <?php 
                                    foreach ($supplier_list as $single_supplier) 
                                    {
                                    ?>
                                        <option value="<?php echo $single_supplier->id; ?>">
                                            <?php echo $single_supplier->customer_name; ?>     
                                        </option>
                                    <?php 
                                     }
                                    ?>
                                </select>
                                
                            </div>
                            <small> who is the provider of puchasing items</small>
                        </div>                
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <?php echo form_label('Store'); ?>
                                <select name="pur_store" class="form-control input-lg">
                                    <?php 
                                        if($store_list != NULL)
                                        {
                                            foreach ($store_list as $single_store) 
                                            {
                                    ?>
                                                <option value="<?php echo $single_store->id; ?>"><?php echo $single_store->name; ?> 
                                                </option>
                                    <?php
                                            }
                                        }

                                     ?>
                                </select>
                            </div>
                            <small>for which store you are purchasing </small>
                        </div>                
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <?php echo form_label('Bill No'); ?>
                                <?php
                                    $data = array('class'=>'form-control input-lg','type'=>'number','name'=>'pur_invoice','placeholder'=>'e.g 255','reqiured'=>'');
                                    echo form_input($data);
                                ?>
                            </div>
                            <small>mention the bill no provided by supplier </small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="purchase-heading"><i class="fa fa-check-circle"></i>  Purchase Detail :</h4>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <?php echo form_label('Grand Total'); ?>
                                <?php
                                    $data = array('class'=>'form-control input-lg','type'=>'number','name'=>'pur_total','id'=>'grand_total','step'=>'.01','reqiured'=>'');
                                    echo form_input($data);
                                ?>
                            </div>
                            <small>mention the amount of total bill </small>                
                        </div>                    
                        <div class="col-md-3">                
                              <div class="form-group">
                                    <label>Upload Bill Image</label>
                                     <div class="input-group">
                                        <input type="file" name="pur_picture" data-validate="required" class="form-control input-lg" data-message-required="Value Required" >
                                    </div>
                              </div>
                              <small>capture or scan bill image  </small>
                        </div>                 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="purchase-heading"><i class="fa fa-check-circle"></i>  Payment Detail :</h4>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php echo form_label('Payment Method'); ?>
                                <select name="pur_method" class="form-control input-lg">
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>
                            <small>method of payment cash or cheque  </small>
                        </div>                
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php echo form_label('Payment Date'); ?>
                                <?php
                                    $data = array('class'=>'form-control input-lg','type'=>'date','name'=>'pur_date','reqiured'=>'');
                                    echo form_input($data);
                                ?>
                            </div>
                            <small>date of bill paid  </small>
                        </div>                
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php echo form_label('Cash Paid'); ?>
                                <?php
                                    $data = array('class'=>'form-control input-lg','onkeyup'=>'calculate_func(this.value)','type'=>'number','name'=>'pur_paid','id'=>'pur_paid','step'=>'.01','reqiured'=>'');
                                    echo form_input($data);
                                ?>
                            </div>
                            <small>how much you paid against total bill. </small>
                        </div>                
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php echo form_label('Balance'); ?>
                                <?php
                                    $data = array('class'=>'form-control input-lg','type'=>'number','id'=>'balance_field','name'=>'pur_balance','step'=>'.01','reqiured'=>'');
                                    echo form_input($data);
                                ?>
                            </div>
                            <small>remaing amount you need to pay in future.</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h4 class="purchase-heading"><i class="fa fa-check-circle"></i>  Purchase Description :</h4>         
                            <?php
                                $data = array('class'=>'form-control input-lg','type'=>'text','name'=>'pur_description','placeholder'=>'Any description','reqiured'=>'');
                                echo form_input($data);

                                $data = array('type'=>'hidden','name'=>'status','value'=>'0','reqiured'=>'');
                                echo form_input($data);
                            ?>
                        </div>
                        <small>any description you want to add for future help.</small>
                    </div>
                </div>            
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php
                                $data = array('class'=>'btn btn-info btn-flat margin btn-lg pull-right ','type' => 'submit','name'=>'btn_submit_customer','value'=>'true', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> Save Purchase');
                                
                                echo form_button($data);
                             ?>  
                        </div>
                    </div>
                </div>
                <?php form_close(); ?>
        </div>
    </div>
</section>

<script type="text/javascript">
    var timmer;
    function calculate_func(val)
    {
        clearTimeout(timmer);
        timmer = setTimeout(function callback()
          { 
             var grand_total = $('#grand_total').val();         
             var balance =  grand_total-val;
             $('#balance_field').val(balance);      

          }, 800);
    }
</script>
<!-- Bootstrap model  -->
<?php $this->load->view('bootstrap_model.php'); ?>
<!-- Bootstrap model  ends--> 