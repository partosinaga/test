<div class="page-content" style="margin-left:0px; min-height:700px;">
	<!-- BEGIN PAGE HEADER-->
	<div class="row hidden-print">
		<div class="col-md-12">
			<!-- BEGIN PAGE TITLE-->
			<h3 class="page-title text-center" style="font-size:13px;margin-bottom:5px;"><?php echo $profile['company_name']; ?></h3>
			<h3 class="page-title text-center" style="font-size:20px;margin-bottom:5px;">INCOME STATEMENT</h3>
			<h3 class="page-title text-center" style="font-size:13px;margin-bottom:5px;">Amounts in (IDR)</h3>
			<h3 class="page-title text-center" style="font-size:13px;margin-bottom:5px;">As Of <?php echo date("F Y", mktime(0, 0, 0, $month, 1, $year));?></h3>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="invoice">
		<!--div class="row invoice-logo">
			<div class="col-xs-6 invoice-logo-space" style="margin-top:-90px;">
				<img style="width:250px;" src="<?php echo base_url();?>assets/img/logo_<?php echo strtolower(PROJECT_SHORT);?>.png" alt="<?php echo PROJECT;?>"/>
			</div>
		</div-->
		<div class="row">
			<div class="col-xs-12">
                <table class="table table-report" style="margin-top:10px;">
                    <thead>
                    <tr>
                        <th width="300px" class="text-left">DESCRIPTION</th>
                        <th width="120px" class="text-right"><?php echo strtoupper(date("M Y", mktime(0, 0, 0, $month, 1, $year)));?></th>
                        <th width="5px">&nbsp;</th>
                        <th width="120px" class="text-right"><?php echo strtoupper(date('M Y', strtotime(date("Y-m-d", mktime(0, 0, 0, $month, 1, $year))." -1 month")));?></th>
                        <th width="5px">&nbsp;</th>
                        <th width="120px" class="text-right">YTD</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $qry = $this->db->query("SELECT * FROM fxnStatementPL(" . $month . ", " . $year . ") WHERE LTRIM(RTRIM(AccountName)) != ''");
                    if($qry->num_rows() > 0){
                        $group_name = '';
                        $ctg_caption = '';
                        $sub_ctg_caption = '';
                        $sub_ctg_caption_no = 0;
                        $tot_sub_ctg_current = 0;
                        $tot_sub_ctg_last_month = 0;
                        $tot_sub_ctg_ytd = 0;
                        $tot_ctg_current = 0;
                        $tot_ctg_last_month = 0;
                        $tot_ctg_ytd = 0;
                        $tot_grp_current = 0;
                        $tot_grp_last_month = 0;
                        $tot_grp_ytd = 0;

                        $prev_month = date('n', strtotime(date("Y-m-d", mktime(0, 0, 0, $month, 1, $year))." -1 month"));
                        $prev_year = date('Y', strtotime(date("Y-m-d", mktime(0, 0, 0, $month, 1, $year))." -1 month"));

                        $i = 0;
                        foreach($qry->result() as $row){

                            if($sub_ctg_caption != $row->SubCtgCaption){
                                if($sub_ctg_caption_no > 1){
                                    echo '<tr>
								<td style="padding-left:20px;"><strong>TOTAL ' . $sub_ctg_caption . '</strong></td>
								<td class="text-right r-border">' . amount_journal($tot_sub_ctg_current) . '</td>
								<td>&nbsp;</td>
								<td class="text-right r-border">' . amount_journal($tot_sub_ctg_last_month) . '</td>
								<td>&nbsp;</td>
								<td class="text-right r-border">' . amount_journal($tot_sub_ctg_ytd) . '</td>
							</tr>';
                                }
                                $tot_sub_ctg_current = 0;
                                $tot_sub_ctg_last_month = 0;
                                $tot_sub_ctg_ytd = 0;

                                $sub_ctg_caption_no = 0;
                            }

                            if($ctg_caption != $row->CtgCaption){
                                if($ctg_caption != ''){
                                    echo '<tr>
								<td><strong>TOTAL ' . $ctg_caption . '</strong></td>
								<td class="text-right r-border">' . amount_journal($tot_ctg_current) . '</td>
								<td>&nbsp;</td>
								<td class="text-right r-border">' . amount_journal($tot_ctg_last_month) . '</td>
								<td>&nbsp;</td>
								<td class="text-right r-border">' . amount_journal($tot_ctg_ytd) . '</td>
							</tr>';

                                    $tot_ctg_current = 0;
                                    $tot_ctg_last_month = 0;
                                    $tot_ctg_ytd = 0;
                                }
                            }

                            if($group_name != $row->GroupName){
                                if($group_name != ''){
                                    echo '<tr>
								<td><strong>' . $group_name . '</strong></td>
								<td class="text-right r-border r-background">' . amount_journal($tot_grp_current) . '</td>
								<td>&nbsp;</td>
								<td class="text-right r-border r-background">' . amount_journal($tot_grp_last_month) . '</td>
								<td>&nbsp;</td>
								<td class="text-right r-border r-background">' . amount_journal($tot_grp_ytd) . '</td>
							</tr>';
                                }

                                $group_name = $row->GroupName;
                            }

                            if($ctg_caption != $row->CtgCaption){
                                echo '<tr>
							<td colspan="6"><strong>' . $row->CtgCaption . '</strong></td>
						</tr>';

                                $ctg_caption = $row->CtgCaption;
                            }

                            if($sub_ctg_caption != $row->SubCtgCaption){

                                echo '<tr>
							<td colspan="6" style="padding-left:20px;"><strong>' . $row->SubCtgCaption . '</strong></td>
						</tr>';

                                $sub_ctg_caption = $row->SubCtgCaption;
                            }

                            echo '<tr>
						<td class="' . (abs($row->CurrentBalance) > 0 ? 'link-detail' : '') . '" style="padding-left:40px;" onclick="open_detail(\'' . $month . '\', \'' . $year . '\', \'' . $row->CtgCaption . '\', \'' . $row->SubCtgCaption . '\', \'' . $row->AccountName . '\', ' . abs($row->CurrentBalance) . ');">' . $row->AccountName . '</td>
						<td class="text-right ' . (abs($row->CurrentBalance) > 0 ? 'link-detail' : '') . '" onclick="open_detail(\'' . $month . '\', \'' . $year . '\', \'' . $row->CtgCaption . '\', \'' . $row->SubCtgCaption . '\', \'' . $row->AccountName . '\', ' . abs($row->CurrentBalance) . ');">' . amount_journal($row->CurrentBalance) . '</td>
						<td>&nbsp;</td>
						<td class="text-right ' . (abs($row->LastBalance) > 0 ? 'link-detail' : '') . '" onclick="open_detail(\'' . $prev_month . '\', \'' . $prev_year . '\', \'' . $row->CtgCaption . '\', \'' . $row->SubCtgCaption . '\', \'' . $row->AccountName . '\', ' . abs($row->LastBalance) . ');">' . amount_journal($row->LastBalance) . '</td>
						<td>&nbsp;</td>
						<td class="text-right ' . (abs($row->YTDCurrent) > 0 ? 'link-detail' : '') . '" onclick="open_detail_ytd(\'' . $month . '\', \'' . $year . '\', \'' . $row->CtgCaption . '\', \'' . $row->SubCtgCaption . '\', \'' . $row->AccountName . '\', ' . abs($row->YTDCurrent) . ');">' . amount_journal($row->YTDCurrent) . '</td>
					</tr>';

                            $tot_sub_ctg_current = $tot_sub_ctg_current + $row->CurrentBalance;
                            $tot_sub_ctg_last_month = $tot_sub_ctg_last_month + $row->LastBalance;
                            $tot_sub_ctg_ytd = $tot_sub_ctg_ytd + $row->YTDCurrent;

                            $tot_ctg_current = $tot_ctg_current + $row->CurrentBalance;
                            $tot_ctg_last_month = $tot_ctg_last_month + $row->LastBalance;
                            $tot_ctg_ytd = $tot_ctg_ytd + $row->YTDCurrent;

                            $pos = strpos(strtolower($row->CtgCaption), 'income');

                            if($pos === false){
                                $tot_grp_current = $tot_grp_current - $row->CurrentBalance;
                                $tot_grp_last_month = $tot_grp_last_month - $row->LastBalance;
                                $tot_grp_ytd = $tot_grp_ytd - $row->YTDCurrent;
                            }
                            else {
                                $tot_grp_current = $tot_grp_current + $row->CurrentBalance;
                                $tot_grp_last_month = $tot_grp_last_month + $row->LastBalance;
                                $tot_grp_ytd = $tot_grp_ytd + $row->YTDCurrent;
                            }

                            $i++;
                            $sub_ctg_caption_no++;

                            if($i == count($qry->result())){
                                if($ctg_caption != ''){
                                    echo '<tr>
								<td><strong>TOTAL ' . $ctg_caption . '</strong></td>
								<td class="text-right r-border">' . amount_journal($tot_ctg_current) . '</td>
								<td>&nbsp;</td>
								<td class="text-right r-border">' . amount_journal($tot_ctg_last_month) . '</td>
								<td>&nbsp;</td>
								<td class="text-right r-border">' . amount_journal($tot_ctg_ytd) . '</td>
							</tr>';

                                    $tot_ctg_current = 0;
                                    $tot_ctg_last_month = 0;
                                    $tot_ctg_ytd = 0;
                                }

                                if($group_name != ''){
                                    echo '<tr>
								<td><strong>' . $group_name . '</strong></td>
								<td class="text-right r-border r-background">' . amount_journal($tot_grp_current) . '</td>
								<td>&nbsp;</td>
								<td class="text-right r-border r-background">' . amount_journal($tot_grp_last_month) . '</td>
								<td>&nbsp;</td>
								<td class="text-right r-border r-background">' . amount_journal($tot_grp_ytd) . '</td>
							</tr>';

                                    $tot_grp_current = 0;
                                    $tot_grp_last_month = 0;
                                    $tot_grp_ytd = 0;
                                }
                            }
                        }
                    }
                    ?>
                    </tbody>
                </table>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
</div>

