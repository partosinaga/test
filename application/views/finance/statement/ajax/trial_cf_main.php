<table class="table table-report" style="margin-top:15px;">
    <thead>
    <tr>
        <th width="9%%" class="text-center">CODE</th>
        <th width="43%" class="text-left">DESCRIPTION</th>
        <th width="12%" class="text-right">DEBIT</th>
        <th width="12%" class="text-right">CREDIT</th>
        <th width="12%" class="text-right">BALANCE</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $qry = $this->db->query("SELECT DISTINCT tr.coa_code,coa.coa_desc FROM view_closecf_statement tr
                             JOIN gl_coa coa ON tr.coa_code = coa.coa_code
                             WHERE tr.postedmonth = " . $month . "
                             AND tr.postedyear = " . $year . "
                             AND tr.is_yearly <= 0
                             ORDER BY tr.coa_code ");

    $sum_debit = 0;
    $sum_credit = 0;
    $sum_balance = 0;
    if($qry->num_rows() > 0){
        $i = 0;
        foreach($qry->result_array() as $row){
            $coaCode = $row['coa_code'];

            $debit = 0;
            $qry_debit = $this->db->query("SELECT ISNULL(SUM(tr.local_amount),0) as localAmount FROM view_closecf_statement tr
                             WHERE tr.postedmonth = " . $month . "
                             AND tr.postedyear = " . $year . "
                             AND tr.is_yearly <= 0
                             AND tr.coa_code = " . $coaCode . "
                             AND tr.is_inflow > 0 ");
            if($qry_debit->num_rows() > 0){
                $debit = $qry_debit->row()->localAmount;
            }

            $credit = 0;
            $qry_credit = $this->db->query("SELECT ISNULL(SUM(tr.local_amount),0) as localAmount FROM view_closecf_statement tr
                             WHERE tr.postedmonth = " . $month . "
                             AND tr.postedyear = " . $year . "
                             AND tr.is_yearly <= 0
                             AND tr.coa_code = " . $coaCode . "
                             AND tr.is_inflow <= 0 ");
            if($qry_credit->num_rows() > 0){
                $credit = $qry_credit->row()->localAmount;
            }

            $balance = $debit - $credit;
    ?>
        <tr class="link-detail">
            <td class="text-center"><?php echo $row['coa_code']; ?></td>
            <td><?php echo $row['coa_desc']; ?></td>
            <td class="text-right"><?php echo amount_journal($debit); ?></td>
            <td class="text-right"><?php echo amount_journal($credit); ?></td>
            <td class="text-right"><?php echo amount_journal($balance); ?></td>
        </tr>
    <?php
            $i++;
            $sum_debit += $debit;
            $sum_credit += $credit;
            $sum_balance += $balance;
        }
    }

    ?>
    </tbody>
    <tfoot style="border-top: 2px solid #333;">
    <?php
    echo "<tr style='font-weight:bold;'>
                <td colspan='2' class='text-right' >TOTAL</td>
                <td class='text-right'>". amount_journal($sum_debit) .  "</td>
                <td class='text-right'>". amount_journal($sum_credit) .  "</td>
                <td class='text-right'>" . amount_journal($sum_balance) . "</td>
              </tr>";
    ?>
    </tfoot>
</table>