const invoiceInfo = (feeInfo = null) => {
    const school = feeInfo.school;

    const invoice = `
                    <div style="font-size:10px;border-style: dashed ; border-width:0.1px;
                     width:44mm; font-family: "Times New Roman", Times, serif;">
                    <center>
                    <h3 >Fee Payment Reciept</h3>
                     Reciept No: ${school.short_name}
                    </center>
                    <table style="font-size:10px;width:80%; font-family: " serif;">
                    <tr>
                    <td>
                    ${school.name},<br/>

 ${school.contact_address},&nbsp;<br/>
 ${school.state}<br/>
Phone:&nbsp; ${school.phone}. <br/>
E: ${school.email}<br/>
URL:&nbsp; ${school.website}.
</td>

                    </tr>

                    </table>
                  <span>
                    <b>Name:</b>&nbsp;${feeInfo.name} ,

                  </span><br/>
                  <span>
                    <b>Class:</b>&nbsp;${feeInfo.feegroup.levels.level_name},
                    &nbsp;
                  </span>
                      <table style=" margin:5px;width:98%;text-align: left;">
                      <thead >
                        <tr>
                        <td>S/N</td>
                        <td>DSC</td>
                        <td>AMT(NGN) </td>
                       </tr>
                       </thead>
                       <tbody>
                       <tr>
                       <td colspan="3">
                       <hr/>
                       </td>
                       </tr>
                      ${createTable(feeInfo.feegroup.fee_description)}
                      <tr>
                       <td colspan="3">
                       <hr/>
                       </td>
                       </tr>
                       <tr >
                       <td>
                       EXP AMT
                       </td>
                       <td></td><td>${feeInfo.feeSum.toFixed(2)}</td>
                       </tr>
                       <tr >
                       <td>
                       AMT PD
                       </td>
                       <td></td>
                       <td>${
                           feeInfo.amount_paid
                               ? Number(feeInfo.amount_paid).toFixed(2)
                               : "0.00"
                       }</td>
                       </tr>
                       <tr >
                       <td>
                       BAL
                       </td>
                       <td></td><td>${(
                           Number(feeInfo.feeSum) - Number(feeInfo.amount_paid)
                       ).toFixed(2)}</td>
                       </tr>
                    </tbody>
                </table>

<center>
<p>
Thank for your patronage...
</p>
</center>
                    </div>

                    `;
    return invoice;
};

const createTable = (reports) => {
    let tr = "";
    reports.map((report, index) => {
        tr += `
                    <tr style="">
                    <center>
                    <td>${index + 1}</td>
                    <td>${report.description}</td>
                    <td>${report.amount.toFixed(2)} </td>
                    </center>
                     </tr>
                      `;
    });
    return tr;
};

export default invoiceInfo;
