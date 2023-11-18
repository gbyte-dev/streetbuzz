<?php echo $this->load->view("emailer/header",array(),TRUE); ?>
<tr>
    <td style="background-color:#ffffff;"></td>
</tr>
<tr>
    <td style="background-color:#ffffff;"></td>
</tr>
<tr>
    <td style="padding:0 30px 0 30px;background-color:#ffffff;">
        <p style="font-size:18px;font-family:Calibri;margin:0px;padding:0;font-weight:bold;line-height:50px;">Hi <?php echo $fullname; ?>,</p>
    </td>
</tr>
<tr>
    <td style="background-color:#ffffff;"></td>
</tr>

<tr>
    <td style="padding:0 30px 10px 30px;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;font-size:14px;">
       Please use this OTP code <span class="green-color" ><b><?php echo $otp;?></b></span> to set a new password of your choice.</td>
</tr>

<tr>
    <td style="padding:0 30px 10px 30px;background-color:#ffffff;font-family:Arial, Helvetica, sans-serif;font-size:14px;">
        Cheers,<br>
        <?php echo SITE_NAME; ?> Team
    </td>
</tr>
<tr>
    <td style="background-color:#ffffff;"></td>
</tr>
<tr>
    <td style="background-color:#ffffff;"></td>
</tr>
<tr>
    <td style="background-color:#ffffff;"></td>
</tr>
<tr>
    <td></td>
</tr>
<?php echo $this->load->view("emailer/footer",array(),TRUE); ?>