<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Request List</h4>
</div>
<div class="modal-body modal-body-scroll">
    <div class="row">
        <div class="col-md-12">
            <div class="table-container">
                <table class="table table-striped table-hover table-bordered" id="datatable_request">
                    <thead>
                    <tr>
                        <th class="text-center"> # </th>
                        <th class="text-center"> Request Code </th>
                        <th class="text-center"> Request Date </th>
                        <th class="text-center"> Dept. </th>
                        <th class="text-center"> Request By </th>
                        <th class="text-center"> Remarks </th>
                        <th width="80px"></th>
                    </tr>
                    <tr role="row" class="filter bg-grey-steel">
                        <td></td>
                        <td><input type="text" class="form-control form-filter input-sm" name="filter_request_code"></td>
                        <td>
                            <div class="input-group date date-picker margin-bottom-5" data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control form-filter input-sm" readonly name="filter_request_date_from" placeholder="From">
                                <span class="input-group-btn">
                                <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                            <div class="input-group date date-picker" data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control form-filter input-sm" readonly name="filter_request_date_to" placeholder="To">
                                <span class="input-group-btn">
                                <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </td>
                        <td>
                            <select class="form-control form-filter input-sm select2me" name="filter_department_id">
                                <option value="">All</option>
                                <?php
                                $qry_dept = $this->mdl_general->get('ms_department', array('status' => STATUS_NEW), array(), 'department_name ASC');
                                foreach($qry_dept->result() as $row_dept){
                                    echo '<option value="' . $row_dept->department_id . '">' . $row_dept->department_name . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td><input type="text" class="form-control form-filter input-sm" name="filter_request_by"></td>
                        <td><input type="text" class="form-control form-filter input-sm" name="filter_remarks"></td>
                        <td>
                            <div class="text-center">
                                <button class="btn btn-sm yellow filter-submit margin-bottom tooltips" data-original-title="Filter" data-placement="top" data-container="body"><i class="fa fa-search"></i></button>
                                <button class="btn btn-sm red filter-cancel tooltips" data-original-title="Reset" data-placement="top" data-container="body"><i class="fa fa-times"></i></button>
                            </div>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                </table>
            </div>
        </div>
    </div>
</div>