<section id="Repay_detail">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0"><img height="20px"
                                  src="{{URL::asset('assets/icons/logistic_charges.png')}}"
                                  alt="Charges" class="pe-2"> <span>Check Repay Amount</span></h5>
        </div>
        <div class="card-body p-3 pb-1">
            <div class="row g-4 mb-4">

                <div class="table-responsive">
                    <table class="table text-dark table-striped">
                        <tbody>
                        <tr class="bg-light">
                            <th>Payment</th>
                            <th>Due Date</th>
                            <th>Interest</th>
                            <th>Extra Charges</th>
                            <th align="right" class="text-end ">Repayable Amount</th>
                        </tr>
                        <tr>
                            <td>Rp {{number_format($returnData['loanDetails']['principal'],2)}}</td>
                            <td>{{\Carbon\Carbon::parse($returnData['loanDetails']['dueDate'])->format('d-m-Y')}}</td>
                            <td>Rp {{number_format($returnData['loanDetails']['interest'],2)}}</td>
                            <td>Rp {{number_format($returnData['loanDetails']['lateFee'],2)}}</td>
                            <td align="right">Rp {{number_format($returnData['loanDetails']['totalOutstanding'],2)}}</td>
                        </tr>
                        <!-- This section will hidden from supplier -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
