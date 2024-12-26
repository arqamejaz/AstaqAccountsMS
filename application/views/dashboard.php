<section class=" content ">
    <div class="row">
        <div class="col-md-10 col-sm-12 pull-left ">			
            <h4 class="company-name"> <i class="fa fa-arrow-circle-right"></i> <?php echo $default[0]->companyname; ?> </h4>
            <small><?php echo $default[0]->companydescription; ?></small>
        </div>
        <div class="col-md-2 col-sm-12 ">
            <a href="<?php echo base_url('homepage'); ?>">
				<?php echo img(array('width'=>'50','height'=>'50','class'=>' pull-right company-icon','alt'=>'User Image','src'=>'uploads/systemimgs/'.$this->db->get_where('mp_langingpage', array('id' =>1))->result_array()[0]['logo'])); ?>        
			</a>
        </div>
	</div>
    <div class="row dash-header">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box custom-titles-color">
                <div class="inner">
                    <h3><label class="dash_amount"><?php echo number_format($cash_in_hand,2,'.','');?></label>
                    </h3>
                    <h4 class="paragraph">Cash in hand <?php echo $default[0]->currency; ?>
                    </h4>
                </div>
                <a href="<?php echo base_url('statements/ledger_accounts');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box custom-titles-color">
                <div class="inner">
                    <h3><label class="dash_amount "><?php echo number_format($account_recieveble,2,'.','');?></label></h3>

                    <h4 class="paragraph">Receivables <?php echo $default[0]->currency; ?></h4>
                </div>
                <a href="<?php echo base_url('statements/ledger_accounts');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box custom-titles-color">
                <div class="inner">
                    <?php
                        if($payables < 0)
                        {
                            $payables = '('.-(number_format($payables,2,'.','')).')';
                        }

                    ?>
                    <h3><label class="dash_amount "><?php echo $payables ;?></label></h3>
                    <h4 class="paragraph">Payables <?php echo $default[0]->currency; ?></h4>
                </div>
                <a href="<?php echo base_url('statements/ledger_accounts');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box custom-titles-color">
                <div class="inner">
                    <h3><label class="dash_amount "><?php echo number_format($cash_in_bank,2,'.','');?></label></h3>
                    <h4 class="paragraph">Cash in bank <?php echo $default[0]->currency; ?></h4>
                </div>
                <a href="<?php echo base_url('statements/ledger_accounts');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
           <h3 class="dash-heading"> <i class="fa fa-arrow-circle-right"></i> Overdue Invoices & Paid expenses </h3>
           <div class="dash-inner-detail">
               <h4 class="dash-heading" >Overdue Invoices</h4>
               <ul>
                <?php
                if($over_due_invoices != NULL)
                {
                    foreach ($over_due_invoices as $single)
                    {
                ?>
                    <li ><a href="<?php echo base_url('prints/invoice_print/'.$single->id); ?>"><?php echo 'Invoice No '.$single->id.' Payee '.$single->customer_name.' '.$default[0]->currency.' Total '.$single->total_bill; ?>  </a>
                    </li>
                <?php
                    }
                }
                else
                {
                    echo '<h4>'.'<i class="fa fa-question-circle"></i> '.'No overdue invoice found'.'</h4>';
                }
                ?>
               </ul>
               <h4 class="dash-heading" >Month paid expenses </h4>
               <ul>
                <?php
                if($month_expenses != NULL)
                {
                    foreach ($month_expenses as $expense)
                    {
                ?>
                   <li><a href="<?php echo base_url('prints/expense/'.$expense->id); ?>"><?php echo 'Expense No '.$expense->id.' '.$default[0]->currency.' Total '.$expense->total_bill.' Payment  '.$expense->method; ?>  </a></li>
                   <?php
                    }
                }
                else
                {
                    echo '<h4>'.'<i class="fa fa-question-circle"></i> '.'No expense found'.'</h4>';
                }
                ?>
               </ul>
           </div>

        </div>
        <div class="col-md-7">
            <h3 class="dash-heading"> <i class="fa fa-arrow-circle-right"></i> Net Income </h3>
            <table class="table table-striped table-bordered">
                <?php
                    $year = Date('Y');
                    $count_year = count($get_incomes['revenue']);
                    if($count_year > 0 )
                    {
                ?>
                <tr>
                    <td>Fiscal Year</td>
                    <?php
                        for($i = 0; $i < $count_year ; $i++)
                        {
                    ?>
                             <td><?php echo $year-$i; ?></td>
                    <?php
                        }
                    ?>
                </tr>
                <tr>
                    <td>Income</td>
                    <?php
                        for($i = 0; $i < $count_year ; $i++)
                        {
                    ?>
                            <td><?php print_r($get_incomes['revenue'][$i]); ?> </td>
                    <?php
                        }
                    ?>

                </tr>
                <tr>
                    <td>Expense</td>
                   <?php
                        for($i = 0; $i < $count_year ; $i++)
                        {
                    ?>
                            <td><?php print_r($get_incomes['expense'][$i]); ?> </td>
                    <?php
                        }
                    ?>

                </tr>
                <tr>
                    <td>Net Income  </td>
                    <?php
                        for($i = 0; $i < $count_year ; $i++)
                        {
                    ?>
                            <td><?php print_r($get_incomes['revenue'][$i] - $get_incomes['expense'][$i]); ?> </td>
                    <?php
                        }
                    }
                    else
                    {
                        echo '<i class="fa fa-question-circle"></i> '." No income record found";
                    }
                    ?>
                </tr>
            </table>
            <h3 class="dash-heading" > <i class="fa fa-arrow-circle-right"></i> Business expenses (this month) <?php echo $default[0]->currency; ?></h3>
             <div  class="pie_chart box-body">
                <canvas id="pieChart" style="height:200px"></canvas>
             </div>
        </div>
    </div>
</section>
<script>
 $(function ()
 {
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var PieData =  <?php echo $get_expense; ?>;
    var pieOptions = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke: true,
      //String - The colour of each segment stroke
      segmentStrokeColor: "#fff",
      //Number - The width of each segment stroke
      segmentStrokeWidth: 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps: 100,
      //String - Animation easing effect
      animationEasing: "easeOutBounce",
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate: true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale: false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);

  });
</script>
