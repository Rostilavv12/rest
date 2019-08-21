<?php

use yii\helpers\Url;

?>
<tr>
    <td style="padding:50px 0 20px;text-align: left">
        <span style="font-size: 30px;font-weight: 700">Hello!</span>
    </td>
</tr>
<tr>
    <td style="color: #464a54;padding:0 0 15px;text-align: left; font-size: 16px">
        Please select the following link to reset your password:
        <a style="display: inline-block;padding: 10px;color: #245160;"
           href="<?= Yii::$app->params['adminDomain'] . Url::to(['/reset-password', 'token' => $code]) ?>"
           target="_blank">Reset Password</a>
    </td>
</tr>
<tr>
    <td style="color: #464a54;padding:0 0 30px;text-align: left; font-size: 16px">
        If you did not request to reset your password, then no further action is required.
    </td>
</tr>
<tr>
    <td style="color: #464a54;padding:0 0 15px;text-align: left; font-size: 16px">
        Thank you,
    </td>
</tr>
<tr>
    <td style="color: #464a54;padding:0 0 15px;text-align: left; font-size: 16px">
        REST API team
    </td>
</tr>
