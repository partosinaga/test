<?php
$isedit = true;

$btn_action = '';
$btn_save = '';
$btn_save .= '<button type="submit" class="btn blue-madison yellow-stripe" name="save"><i class="fa fa-save"></i> &nbsp;&nbsp;Save </button>';
$btn_save .= '&nbsp;<button type="submit" class="btn blue-madison yellow-stripe" name="save_close"><i class="fa fa-sign-in"></i> &nbsp;&nbsp;Save & Close </button>';

if($sr_id > 0){
    if ($row->status == STATUS_NEW) {
        if (!check_session_action(get_menu_id(), STATUS_EDIT)) {
            $isedit = false;
        }
        if (check_session_action(get_menu_id(), STATUS_POSTED)) {
            $btn_action .= '&nbsp;<a class="btn yellow-gold btn-bootbox btn-action" data-action="' . STATUS_POSTED . '" data-id="' . $row->sr_id . '" data-code="' . $row->sr_code . '"><i class="fa fa-thumbs-o-up"></i>&nbsp;&nbsp;' . ucwords(strtolower(get_action_name(STATUS_POSTED, false))) . '</a>';
        }
        if (check_session_action(get_menu_id(), STATUS_CANCEL)) {
            $btn_action .= '&nbsp;<a class="btn yellow-gold btn-bootbox btn-action" data-action="' . STATUS_CANCEL . '" data-id="' . $row->sr_id . '" data-code="' . $row->sr_code . '"><i class="fa fa-exclamation-triangle "></i> &nbsp;&nbsp;' . ucwords(strtolower(get_action_name(STATUS_CANCEL, false))) . '</a>';
        }
    } else if ($row->status == STATUS_POSTED) {
        $isedit = false;
        $btn_action .= '&nbsp;<a href="' . base_url('inventory/stock_receipt/pdf_receipt/' . $sr_id . '.tpd') . '" target="_blank" class="btn yellow-gold"><i class="fa fa-print"></i> &nbsp;&nbsp;Print</a>';
    } else if ($row->status == STATUS_CANCEL) {
        $isedit = false;
    }

} else {
    if (!check_session_action(get_menu_id(), STATUS_NEW)) {
        $isedit = false;
    }
}

$btn = ($isedit ? $btn_save : '') . $btn_action;

?>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
		<!-- BEGIN PAGE HEADER-->
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<?php
					$breadcrumbs = get_menu_name($this->uri->segment(1), $this->uri->segment(2), $this->uri->segment(3));
					foreach($breadcrumbs as $breadcrumb){
						echo $breadcrumb;
					}
				?>
			</ul>
		</div>
		<!-- END PAGE HEADER-->
		<!-- BEGIN PAGE CONTENT-->
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box <?php echo BOX;?>">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-user"></i><?php echo ($sr_id > 0 ? 'View' : 'New');?> Stock Receipt
                            <?php
                            if($sr_id > 0){
                                echo '&nbsp;&nbsp;&nbsp;' . get_status_name($row->status);
                            }
                            ?>
						</div>
						<div class="actions">
                            <?php
                            $url = base_url('inventory/stock_receipt/receipt_manage.tpd');
                            if ($sr_id > 0) {
                                if ($row->status == STATUS_NEW || $row->status == STATUS_DISAPPROVE) {} else {
                                    base_url('inventory/stock_receipt/receipt_history.tpd');
                                }
                            }
                            echo btn_back($url);
                            ?>
						</div>
					</div>
					<div class="portlet-body form">
						<form method="post" id="id_form_input" class="form-horizontal" onsubmit="return false">
							<input type="hidden" name="sr_id" value="<?php echo $sr_id;?>" />
							<div class="form-actions top">
								<div class="row">
									<div class="col-md-9">
                                        <?php
                                            echo $btn;
                                        ?>
									</div>
								</div>
							</div>
							<div class="form-body" id="form-entry">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-4">Receipt Code</label>
											<div class="col-md-8">
												<input type="text" class="form-control" value="<?php echo ($sr_id > 0 ? $row->sr_code : '');?>" readonly />
											</div>
										</div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Stock Issue Code</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input type="hidden" name="gi_id" value="<?php echo ($sr_id > 0 ? $row->gi_id : '0');?>" />
                                                    <input class="form-control" id="gi_code" type="text" value="<?php echo ($sr_id > 0 ? $row->gi_code : ''); ?>" readonly >
                                                    <span class="input-group-btn">
                                                        <a id="btn_lookup_gi" class="btn btn-success" href="javascript:;">
                                                            <i class="fa fa-arrow-up fa-fw"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
									</div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Receipt Dates</label>
                                            <div class="col-md-6">
                                                <div class="input-group date date-picker" data-date-format="dd-mm-yyyy">
                                                    <input type="text" class="form-control" name="sr_date" value="<?php echo ($sr_id > 0 ? ymd_to_dmy($row->sr_date) : date('d-m-Y'));?>" readonly>
													<span class="input-group-btn">
														<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
													</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-2">Remarks</label>
                                            <div class="col-md-10">
                                                <textarea class="form-control" rows="2" name="remarks"><?php echo ($sr_id > 0 ? $row->remarks : '');?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a class="btn btn-sm green-turquoise yellow-stripe" id="btn_add_detail" style="margin-bottom: 10px;">
                                            <i class="fa fa-plus"></i>
                                            <span> &nbsp;&nbsp;Add Detail </span>
                                        </a>
                                    </div>
                                </div>
								<div class="row">
									<div class="col-md-12">
										<table class="table table-striped table-hover table-bordered" id="datatable_detail">
											<thead>
												<tr>
													<th width="15%" class="text-center"> Item Code </th>
													<th class="text-center"> Description </th>
                                                    <th width="12%" class="text-center"> UOM </th>
													<th width="12%" class="text-center"> Issue Qty </th>
													<th width="12%" class="text-center"> Qty </th>
                                                    <th width="60px" class="text-center"> Action </th>
												</tr>
											</thead>
											<tbody>
                                            <?php
                                            if($sr_id > 0){
                                                $i = 1;
                                                foreach($qry_detail->result() as $row_detail){
                                                    $append_html = '';
                                                    $append_html .= '<tr>';
                                                    $append_html .=      '<input type="hidden" name="sr_detail_id[' . $i . ']" value="' . $row_detail->sr_detail_id . '"/>';
                                                    $append_html .=      '<input class="input_gi_detail_id" type="hidden" name="gi_detail_id[' . $i . ']" value="' . $row_detail->gi_detail_id . '"/>';
                                                    $append_html .=      '<input type="hidden" name="item_id[' . $i . ']" value="' . $row_detail->item_id . '"/>';
                                                    $append_html .=      '<input type="hidden" name="unit_cost[' . $i . ']" value="' . $row_detail->unit_cost . '"/>';
                                                    $append_html .=      '<input class="class_status" type="hidden" name="status[' . $i . ']" value="1"/>';
                                                    $append_html .=      '<td class="text-center padding-top-13">' . $row_detail->item_code . '</td>';
                                                    $append_html .=      '<td class="padding-top-13">' . $row_detail->item_desc . '</td>';
                                                    $append_html .=      '<td class="text-center padding-top-13">' . $row_detail->uom_out_code . '</td>';
                                                    $append_html .=      '<td class="text-right padding-top-13">' . $row_detail->gi_qty . '</td>';
                                                    $append_html .=      '<td><input type="text" class="text-right form-control mask_number input-sm num_qty" name="item_qty[' . $i . ']" value="' . $row_detail->item_qty . '" data-max="' . ($row_detail->gi_qty_remain + $row_detail->item_qty) . '" /></td>';
                                                    $append_html .=      '<td align="center" class="padding-top-13"><a class="btn btn-xs red tooltips btn-delete" href="javascript:;" data-container="body" data-placement="top" data-original-title="Delete" data-index="' . $i . '"><i class="fa fa-remove"></i></a></td>';
                                                    $append_html .= '</tr>';

                                                    echo $append_html;

                                                    $i++;
                                                }
                                            }
                                            ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</form>
						<!-- END FORM-->
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if($sr_id > 0){
                                    echo '<div class="note note-info" style="margin:10px;">';
                                    $qry_log = $this->mdl_general->get('app_log', array('feature_id' => Feature::FEATURE_STOCK_RECEIPT, 'reff_id' => $sr_id), array(), 'log_id asc');
                                    echo '<div class="col-md-8">';
                                    if($qry_log->num_rows() > 0){
                                        echo '<ul class="list-unstyled" style="margin-left:-15px;">';
                                        foreach($qry_log->result() as $row_log){
                                            $remark = '';
                                            if(trim($row_log->remark) != ''){
                                                $remark = '<h4 style="margin-left:10px;"><span class="label label-success">Remark : ' . trim($row_log->remark) . '</span></h4>';
                                            }
                                            echo '<li class="margin-bottom-5"><h6>' . $row_log->log_subject  . ' on ' . date_format(new DateTime($row_log->log_date), 'd/m/Y H:i:s') . ' by ' . get_user_fullname( $row_log->user_id ) . '</h6>' . $remark . '</li>';
                                        }
                                        echo '</ul>';
                                    }
                                    echo '</div>';
                                    if ($row->user_modified > 0) {
                                        echo "<div class='col-md-4'><h6>Last Modified by ".get_user_fullname( $row->user_modified )." (". date_format(new DateTime($row->date_modified), 'd/m/Y H:i:s') .")</h6></div>" ;
                                    }
                                    echo '<div style="clear:both;"></div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE CONTENT-->
	</div>
</div>
<!-- END CONTENT -->

<div id="ajax-modal" class="modal fade" data-replace="true" data-width="900" data-keyboard="false" data-backdrop="static" tabindex="-1"></div>

<script>
	$(document).ready(function(){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "50000",
            //"extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        var $modal = $('#ajax-modal');

        <?php
        if($sr_id > 0){
            echo show_toastr($this->session->flashdata('flash_message'), $this->session->flashdata('flash_message_class'));
            echo picker_input_date(true, true, ymd_to_dmy($row->sr_date));
        } else {
            echo picker_input_date();
        }
        ?>

        var isedit = <?php echo ($isedit ? 0 : 1); ?>;

        if(isedit > 0){
            $('#form-entry').block({
                message: null ,
                overlayCSS: {backgroundColor: '#EDF5EB', opacity:0, cursor:'default'}
            });
        }

        function mask_number() {
            $(".mask_number").inputmask("decimal", {
                radixPoint:".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true,
                autoUnmask: true
            });
        }

        mask_number();

        autosize($('textarea'));

        //Request
        var grid_gi = new Datatable();

        var datatable_gi = function () {
            grid_gi.init({
                src: $("#datatable_gi"),
                onSuccess: function (grid) {
                    // execute some code after table records loaded
                },
                onError: function (grid) {
                    // execute some code on network or other general error
                },
                onDataLoad: function(grid) {
                    // execute some code on ajax data load
                },
                loadingMessage: 'Loading...',
                dataTable: {
                    "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.
                    "aoColumns": [
                        { "bSortable": false, "sClass": "text-center" },
                        null,
                        null,
                        { "bSortable": false, "sClass": "text-center" },
                        { "bSortable": false },
                        { "bSortable": false },
                        { "bSortable": false, "sClass": "text-center" }
                    ],
                    "aaSorting": [],
                    "lengthMenu": [
                        [10, 20, 50, 100, 150, -1],
                        [10, 20, 50, 100, 150, "All"] // change per page values here
                    ],
                    "pageLength": 10, // default record count per page
                    "ajax": {
                        "url": "<?php echo base_url('inventory/stock_receipt/ajax_gi_list');?>/" + $('input[name="gi_id"]').val() // ajax source
                    }
                }
            });

            var tableWrapper = $("#datatable_gi_wrapper");

            tableWrapper.find(".dataTables_length select").select2({
                showSearchInput: false //hide search box with special css class
            });
        }

        $('#btn_lookup_gi').live('click', function (e) {
            e.preventDefault();

            $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
                '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
                '<div class="progress progress-striped active">' +
                '<div class="progress-bar" style="width: 100%;"></div>' +
                '</div>' +
                '</div>';

            $('body').modalmanager('loading');


            setTimeout(function () {
                $modal.load('<?php echo base_url('inventory/stock_receipt/ajax_modal_gi');?>', '', function () {

                    $modal.modal();

                    datatable_gi();

                    $.fn.modalmanager.defaults.resize = true;

                    if ($modal.hasClass('bootbox') == false) {
                        $modal.addClass('modal-fix');
                    }

                    if ($modal.hasClass('modal-overflow') == false) {
                        $modal.addClass('modal-overflow');
                    }

                    $modal.css({'margin-top': '0px'})

                    $('.select2me').select2();

                });
            }, 100);
        });

        $('.btn-select-gi').live('click', function (e) {
            e.preventDefault();

            var gi_id = $(this).attr('data-id');
            var gi_code = $(this).attr('data-code');

            var old_gi_id = parseInt($('input[name="gi_id"]').val()) || 0;
            var next = true;
            if(old_gi_id > 0){
                bootbox.confirm("Old Stock Issue will be deleted, continue?", function(result) {
                    if (result == true) {
                        $('input[name="gi_id"]').val(gi_id);
                        $('#gi_code').val(gi_code);

                        set_flag_delete(0);
                    }
                    else {
                        next = false;
                    }
                });
            }
            else {
                $('input[name="gi_id"]').val(gi_id);
                $('#gi_code').val(gi_code);
            }

            $('#ajax-modal').modal('hide');
        });

        $('#btn_add_detail').live('click', function (e) {
            e.preventDefault();

            var gi_id = parseInt($('input[name="gi_id"]').val()) || 0;

            if(gi_id > 0) {
                $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
                    '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
                    '<div class="progress progress-striped active">' +
                    '<div class="progress-bar" style="width: 100%;"></div>' +
                    '</div>' +
                    '</div>';

                $('body').modalmanager('loading');

                setTimeout(function () {
                    $modal.load('<?php echo base_url('inventory/stock_receipt/ajax_modal_gi_detail');?>', '', function () {

                        $modal.modal();
                        datatableGIDetail();

                        $.fn.modalmanager.defaults.resize = true;

                        if ($modal.hasClass('bootbox') == false) {
                            $modal.addClass('modal-fix');
                        }

                        if ($modal.hasClass('modal-overflow') == false) {
                            $modal.addClass('modal-overflow');
                        }

                        $modal.css({'margin-top': '0px'})

                    });
                }, 100);
            }
            else {
                toastr["warning"]("Please Select Stock Request.", "Warning");
            }
        });

        var grid_gi_detail = new Datatable();

        var datatableGIDetail = function () {

            var gi_detail_id_exist = '-';
            var n = 0;
            $('#datatable_detail tbody tr').each(function () {
                if ($(this).hasClass('hide') == false) {
                    if(gi_detail_id_exist == '-'){
                        gi_detail_id_exist = '';
                    }
                    if(n > 0) {
                        gi_detail_id_exist += '_';
                    }
                    gi_detail_id_exist += $(this).find(".input_gi_detail_id").val();

                    n++;
                }
            });

            grid_gi_detail.init({
                src: $("#datatable_gi_detail"),
                onSuccess: function (grid) {
                    // execute some code after table records loaded
                },
                onError: function (grid) {
                    // execute some code on network or other general error
                },
                onDataLoad: function(grid) {
                    // execute some code on ajax data load
                },
                loadingMessage: 'Loading...',
                dataTable: {
                    "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.
                    "aoColumns": [
                        { "bSortable": false, "sClass": "text-center" },
                        { "bSortable": false, "sClass": "text-center" },
                        { "bSortable": false },
                        { "bSortable": false, "sClass": "text-center" },
                        { "bSortable": false, "sClass": "text-right" },
                        { "bSortable": false, "sClass": "text-right" }
                    ],
                    "aaSorting": [],
                    "lengthMenu": [
                        [10, 20, 50, 100, 150, -1],
                        [10, 20, 50, 100, 150, "All"] // change per page values here
                    ],
                    "pageLength": 10, // default record count per page
                    "ajax": {
                        "url": "<?php echo base_url('inventory/stock_receipt/ajax_gi_detail_list');?>/" + $('input[name="gi_id"]').val() + "/" + gi_detail_id_exist // ajax source
                    }
                }
            });

            var tableWrapper = $("#datatable_gi_detail_wrapper");

            tableWrapper.find(".dataTables_length select").select2({
                showSearchInput: false //hide search box with special css class
            });

            grid_gi_detail.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
                e.preventDefault();

                var tr_row = $('#datatable_detail tbody tr').length + 1;

                if (grid_gi_detail.getSelectedRowsCount() > 0) {
                    var result = grid_gi_detail.getSelectedRowsCheckbox();

                    $.each( result, function( index, value ){
                        console.log(value);

                        var append_html = append_detail(tr_row, value);

                        $('#datatable_detail tbody').append(append_html);

                        $('.tooltips').tooltip();

                        mask_number();

                        tr_row++;
                    });

                    $('#ajax-modal').modal('hide');
                } else if (grid_gi_detail.getSelectedRowsCount() === 0) {
                    toastr["warning"]("Please Select Detail.", "Warning");
                }
            });
        }

        function append_detail(index, value){
            var max = parseFloat(value[6]) || 0;
            var qty = 1;
            if(max < 1){
                qty = max;
            }
            var append_html = '';
            append_html += '<tr>';
            append_html +=      '<input type="hidden" name="sr_detail_id[' + index + ']" value="0"/>';
            append_html +=      '<input class="input_gi_detail_id" type="hidden" name="gi_detail_id[' + index + ']" value="' + value[0] + '"/>';
            append_html +=      '<input type="hidden" name="item_id[' + index + ']" value="' + value[1] + '"/>';
            append_html +=      '<input type="hidden" name="unit_cost[' + index + ']" value="' + value[7] + '"/>';
            append_html +=      '<input class="class_status" type="hidden" name="status[' + index + ']" value="1"/>';
            append_html +=      '<td class="text-center padding-top-13">' + value[2] + '</td>';
            append_html +=      '<td class="padding-top-13">' + value[3] + '</td>';
            append_html +=      '<td class="text-center padding-top-13">' + value[4] + '</td>';
            append_html +=      '<td class="text-right padding-top-13">' + value[5] + '</td>';
            append_html +=      '<td><input type="text" class="text-right form-control mask_number input-sm num_qty" name="item_qty[' + index + ']" value="' + qty + '" data-max="' + value[6] + '" /></td>';
            append_html +=      '<td align="center" class="padding-top-13"><a class="btn btn-xs red tooltips btn-delete" href="javascript:;" data-container="body" data-placement="top" data-original-title="Delete" data-index="' + index + '"><i class="fa fa-remove"></i></a></td>';
            append_html += '</tr>';

            return append_html;
        }

        $('.num_qty').live('keyup', function(){
            var val = $(this).val().trim();
            var max = parseFloat($(this).attr('data-max')) || 0;
            if(val == ''){
                val = 1;
                $(this).val(1);
            }
            val = parseFloat(val) || 0;
            if(val == 0){
                $(this).val(1);
            }

            if(val > max){
                toastr.clear();

                $(this).val(max);
                toastr["warning"]("Receipt Qty can not bigger than Issue Qty Remain.", "Warning!");
            }
        });

        $('.btn-delete').live('click', function(e){
            e.preventDefault();

            var this_btn = $(this);
            bootbox.confirm("Are you sure want to delete?", function (result) {
                if (result == true) {
                    this_btn.closest('tr').addClass('hide');
                    this_btn.closest('tr').find('.class_status').val('9');
                }
            });
        });

        function set_flag_delete(num_index) {
            if (num_index == 0) {
                $('#datatable_detail tbody tr').each(function () {
                    if ($(this).hasClass('hide') == false) {
                        $(this).addClass('hide');
                    }
                });

                $('#datatable_detail tbody tr input').each(function () {
                    if ($(this).hasClass('class_status')) {
                        $(this).val('9');
                    }
                });
            }
            else {
                if ($('#datatable_detail tbody tr:nth-child(' + num_index + ')').hasClass('hide') == false) {
                    $('#datatable_detail tbody tr:nth-child(' + num_index + ')').addClass('hide');
                }
                $('#datatable_detail tbody tr:nth-child(' + num_index + ') input.class_status').val('9');
            }
        }

        //SUBMIT

        function validate_submit(){
            var result = true;

            if($('.form-group').hasClass('has-error')){
                $('.form-group').removeClass('has-error');
            }

            var gi_id = parseInt($('input[name="gi_id"]').val()) || 0;
            var sr_date = $('input[name="sr_date"]').val().trim();
            var remarks = $('textarea[name="remarks"]').val().trim();

            if(gi_id <= 0){
                toastr["warning"]("Please select Stock Issue.", "Warning!");
                $('input[name="gi_id"]').closest('.form-group').addClass('has-error');
                result = false;
            }
            if(sr_date == ''){
                toastr["warning"]("Please select Receipt Date.", "Warning!");
                $('input[name="sr_date"]').closest('.form-group').addClass('has-error');
                result = false;
            }
            if(remarks == ''){
                toastr["warning"]("Please input remarks.", "Warning!");
                $('textarea[name="remarks"]').closest('.form-group').addClass('has-error');
                result = false;
            }

            var i = 1;
            var i_act = 0;
            $('#datatable_detail > tbody > tr ').each(function() {
                if(!$(this).hasClass('hide')) {
                    var item_qty = parseFloat($('input[name="item_qty[' + i + ']"]').val()) || 0;
                    var qty_max = parseFloat($('input[name="item_qty[' + i + ']"]').attr('data-max')) || 0;

                    $('input[name="item_qty[' + i + ']"]').removeClass('has-error');

                    if(item_qty > qty_max){
                        toastr["warning"]("Item Qty cannot bigger than Request Qty Remain.", "Warning");
                        $('input[name="item_qty[' + i + ']"]').addClass('has-error');
                        result = false;
                    }
                    if(item_qty <= 0){
                        toastr["warning"]("Please select Item Qty.", "Warning");
                        $('input[name="item_qty[' + i + ']"]').addClass('has-error');
                        result = false;
                    }
                    i_act++;
                }
                i++;
            });

            if(i_act <= 0 ){
                toastr["warning"]("Detail cannot be empty.", "Warning");
                result = false;
            }

            return result;
        }

        $('#id_form_input').on('submit', function(){
            Metronic.blockUI({
                target: '#id_form_input',
                boxed: true,
                message: 'Processing...'
            });

            var btn = $(this).find("button[type=submit]:focus" );

            var next = true;
            toastr.clear();

            if(validate_submit()){
                var form_data = $('#id_form_input').serializeArray();
                if (btn[0] == null){ }
                else {
                    if(btn[0].name === 'save_close'){
                        form_data.push({name: "save_close", value: 'save_close'});
                    }
                }

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('inventory/stock_receipt/ajax_stock_receipt_submit');?>",
                    dataType: "json",
                    data: form_data
                })
                    .done(function (msg) {
                        if (msg.success == '1') {
                            window.location.assign(msg.link);
                            //$('#input_form').unblock();
                        }
                        else {
                            toastr["error"](msg.message, "Error");
                            $('#id_form_input').unblock();
                        }
                    })
                    .fail(function () {
                        $('#id_form_input').unblock();
                        toastr["error"]("Something has wrong, please try again later.", "Error");
                    });
            }
            else {
                $('#id_form_input').unblock();
            }
        });

        $('.btn-action').live('click', function(){
            var id = $(this).attr('data-id');
            var action = $(this).attr('data-action');
            var code = $(this).attr('data-code');

            if(action == '<?php echo STATUS_POSTED;?>') {
                var act = 'Posting';
                bootbox.confirm("Are you sure want to " + act + " " + code + " ?", function (result) {
                    if (result == true) {
                        Metronic.blockUI({
                            boxed: true
                        });

                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('inventory/stock_receipt/ajax_action_receipt');?>",
                            dataType: "json",
                            data: {sr_id: id, action: action, is_redirect: true}
                        })
                            .done(function (msg) {
                                Metronic.unblockUI();
                                if (msg.valid == '1') {
                                    location.reload();
                                    //toastr["success"](msg.message, "Success");
                                }
                                else if (msg.valid == '0'){
                                    toastr["error"](msg.message, "Error");
                                }
                            })
                            .fail(function () {
                                Metronic.unblockUI();
                                toastr["error"]("Something has wrong, please try again later.", "Error");
                            });

                    }
                });
            } else if(action == '<?php echo STATUS_CANCEL;?>'){
                var act = 'Cancel';
                bootbox.prompt({
                    title: "Please enter Reason for " + act + " " + code + " :",
                    value: "",
                    buttons: {
                        cancel: {
                            label: "Cancel",
                            className: "btn-inverse"
                        },
                        confirm:{
                            label: "OK",
                            className: "btn-primary"
                        }
                    },
                    callback: function(result) {
                        if(result === null){ }
                        else if(result.length <= 5){
                            toastr["warning"]("Reason must be filled to proceed, Minimum 5 character.", "Warning");
                        } else {
                            Metronic.blockUI({
                                boxed: true
                            });

                            $.ajax({
                                type: "POST",
                                url: "<?php echo base_url('inventory/stock_receipt/ajax_action_receipt');?>",
                                dataType: "json",
                                data: {sr_id: id, action: action, reason:result, is_redirect: true }
                            })
                                .done(function( msg ) {
                                    Metronic.unblockUI();

                                    if (msg.valid == '1') {
                                        location.reload();
                                    } else if (msg.valid == '1') {
                                        toastr["error"](msg.message, "Error");
                                    }
                                })
                                .fail(function () {
                                    Metronic.unblockUI();
                                    toastr["error"]("Something has wrong, please try again later.", "Error");
                                });
                        }
                    }
                });
            }
        });
	});
</script>