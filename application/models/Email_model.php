<?php
/*
*  @author    : Muhammad Ibrahim
*  @Mail      : aliibrahimroshan@gmail.com
*  @Created   : 11th December, 2018
*  @Developed : Team Spantik Lab
*  @URL       : www.spantiklab.com
*  @Envato    : https://codecanyon.net/user/spantiklab
*/
class Email_model extends CI_Model
{ 
   function email_request($mail_data)
    {

        $token = sha1(uniqid($mail_data['payee_id'], true));

        $arg = array(
          'token'   => $token,
          'user_id' => $mail_data['payee_id'],
          'source'  => $mail_data['source'],
          'data_id' => $mail_data['request_no'],
          'tstamp'  => $_SERVER["REQUEST_TIME"]
        );      
       
        $this->db->insert('mp_temp_urls', $arg);
        
        $url_link = base_url('public_access/print_request/'.$token); 

        if ($this->db->affected_rows() > 0)
        {

        $message = '
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>'.$mail_data['title'].'</title>
            <style>
            * {
              margin: 0;
              font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
              box-sizing: border-box;
              font-size: 14px;
            }
            body 
            {
              -webkit-font-smoothing: antialiased;
              -webkit-text-size-adjust: none;
              width: 100% !important;
              height: 100%;
              line-height: 1.6em;
              background-color: #f6f6f6;

            }

            table td 
            {
              vertical-align: top;
            }

            .body-wrap 
            {
              background-color: #f6f6f6;
              width: 100%;
            }

            .main
            {
                border-top:15px solid '.$mail_data['color'].';
            }
            .content 
            {
              max-width: 600px;
              margin: 0 auto;
              display: block;
              border: 1px solid #e9e9e9;
              margin-top:40px;
              margin-bottom:40px;
              border-radius: 3px;
            }
            .content-wrap 
            {
              padding: 0px 20px 20px 20px;
            }

            .content-block 
            {
              padding: 0 0 8px;
            }

            .clear 
            {
              clear: both;
            }
            /* -------------------------------------
                RESPONSIVE AND MOBILE FRIENDLY STYLES
            ------------------------------------- */
            @media only screen and (max-width: 640px) 
            {
              body 
              {
                padding: 0 !important;
              }

              .container 
              {
                padding: 0 !important;
                width: 100% !important;
              }

              .content 
              {
                padding: 0 !important;
              }

              .content-wrap 
              {
                padding: 10px !important;
              }

              .invoice 
              {
                width: 100% !important;
              }
            }
            .company-logo 
            {
              width: 100px;
              margin: 10px;
              height: 100px;
              float: left;
            }
            .email-company-name
            {
              font-size: 24px;
              font-size: 24px;
              line-height: 100px;
              font-weight: bold;
              font-family: arial;
              margin: 11px 0px;
              text-transform: capitalize;
              float: left;
              color:#333;
            }
            .email-body-box
            {
              width: 125px;
              float: left;
              padding:10px 0px;
              font-size:14px;
              font-family: arial;
              text-align: center;
            }
            .email-body-box strong
            {
                font-size:14px;
            }
            .invoice-btn
            {
                margin-left:15px;
                margin-top:10px;
                border:none;
            }
            .invoice-button
            {
                background-color :'.$mail_data['color'].';
                padding:10px;
                border:none;
                border-radius:3px;
                text-decoration: none;
                font-size:16px;
                color:#fff;
            }
            .table-row-area
            {
                background-color:#fff;
                padding:20px;
            }
            .software-by a
            {
                color: #333;
                font-size:10px;
            }
            </style>        
            </head>
            <body>
            <table class="body-wrap">
                <tr>
                    <td></td>
                    <td class="container" width="600">
                        <div class="content">
                            <table class="main" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="alert alert-warning">
                                        <img src="'.$mail_data['logo'].'" class="company-logo" />
                                        <span class="email-company-name">'.$mail_data['company'].'</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-wrap">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style=" border-right:1px solid #ccc;" class="email-body-box">
                                                    '.$mail_data['type'].' <br> <strong> '.$mail_data['request_no'].'</strong>
                                                </td>
                                                <td style=" border-right:1px solid #ccc;" class="email-body-box">
                                                    '.$mail_data['title2'].' <br> <strong> '.$mail_data['due_date'].'</strong>
                                                </td>
                                                <td  style=" border-right:1px solid #ccc;" class="email-body-box">
                                                    '.$mail_data['title1'].' <br> <strong> '.$mail_data['balance'].'</strong>
                                                </td>
                                                <td class="email-body-box invoice-btn">
                                                    <a href="'.$url_link.'" class="invoice-button">
                                                        '.$mail_data['button_text'].'
                                                    </a>    
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <div class="table-row-area">
                                <table>
                                        <tr>
                                            <td class="content-block">
                                                Dear '.$mail_data['customer_name'].',
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="content-block">
                                                Here\'s your '.$mail_data['source'].'! if you have any query please contact us.

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="content-block">
                                                Thanks for your business!
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="content-block">
                                                '.$mail_data['company'].'
                                            </td>
                                        </tr>
                                        <tr>
                                        <td class=" software-by" ><a  href="http://www.gigabyteltd.net"> © Bedana Software </a></td>
                                        </tr>
                                </table>
                            </div>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </table>
            </body>
            </html>';

        $customer_email = $mail_data['customer_email'];
        $from_email = $mail_data['sender_email'];
        $subject = $mail_data['title'];
        $email_desc = $message; 

       
        $this->email->set_header('MIME-Version','1.0\r\n');
        $this->email->set_header('Content-Type','text/html');
        $this->email->from($from_email, $mail_data['company']);
        $this->email->to($customer_email);
        $this->email->subject($subject);
        $this->email->message($email_desc);
        return $this->email->send();

        }
        else
        {
            return FALSE;
        }
    }

}