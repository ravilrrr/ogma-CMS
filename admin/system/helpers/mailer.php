<?php

/**
 *  OGMA CMS Mailer Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Mailer {
    
    
    
    public function __construct() {
        // nothing			
    }
    
    
    public function sendmail($to, $subject, $message) {
        
        $message = $this->email_template($message);
        
        $fromemail = Core::$site['email'];
        
        $headers = '"MIME-Version: 1.0' . PHP_EOL;
        $headers .= 'Content-Type: text/html; charset=UTF-8' . PHP_EOL;
        $headers .= 'From: ' . $fromemail . PHP_EOL;
        $headers .= 'Reply-To: ' . $fromemail . PHP_EOL;
        $headers .= 'Return-Path: ' . $fromemail . PHP_EOL;
        
        if (@mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', "$message", $headers)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function email_template($message) {
        $data = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
		<style>
		 table td p {margin-bottom:15px;}
		 a img {border:none;}
		</style>
		</head>
		<body style="padding:0;margin:0;background: #f3f3f3;font-family:arial, \'helvetica neue\', helvetica, serif" >
		<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding: 0 0 35px 0; background: #f3f3f3;">
			<tr>
				<td align="center" style="margin: 0; padding: 0;">
					<center>
						<table border="0" cellpadding="0" cellspacing="0" width="580" style="border-radius:3px;">
							<tr>
								<td style="background:#fff;border-bottom:1px solid #e1e1e1;border-right:1px solid #e1e1e1;border-left:1px solid #e1e1e1;font-size:13px;font-family:arial, helvetica, sans-serif;padding:20px;line-height:22px;" >
									' . $message . '
								</td>
							</tr>
							<tr>
								<td style="padding-top:10px;font-size:10px;color:#aaa;line-height:14px;font-family:arial, \'helvetica neue\', helvetica, serif" >
									<p class="meta">This is a system-generated email, please do not reply to it. </p>
								</td>
							</tr>
						</table>
					</center>
				</td>
			</tr>
		</table>
		</body>
		</html>
		';
        return $data;
    }
    
    
    
}