<?php

/*
Copyright (c) 2001 Juraj Bednar <juraj@bednar.sk>, all rights reserved.
Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:

  1.Redistributions of source code must retain the above copyright notice,
    this list of conditions and the following disclaimer.  
  2.Redistributions in binary form must reproduce the above copyright
    notice, this list of conditions and the following disclaimer in the
    documentation and/or other materials provided with the distribution.
  3.The name of the author may not be used to endorse or promote products
    derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN
NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. */

//tosign = MID + AMT + CURR + VS + CS + RURL + IPC + NAME

class TatraPay {

   var $cl_mid;		// Merchant ID
   var $cl_key;		// Merchant Key for signing keys

   var $tr_cs;		// constant symbol
   var $tr_vs;		// variable symbol
   var $tr_amt;		// amount
   var $tr_rurl;	// return url
   var $tr_desc;	// transaction description
   var $tr_rsms;	// return sms
   var $tr_rem;		// return email
   var $tr_curr;	// currency (EUR=978)

   var $action_url = "https://moja.tatrabanka.sk/cgi-bin/e-commerce/start/e-commerce.jsp?";
   var $image_src = "/images/tatrapay_logo.gif";
   var $action = "";      // Protokol (parameter PT pre formular)

// Inicializacia modulu
    function TatraPay ($mid, $key, $image="", $action_url="")
    {
        if ($action_url) $this->action_url = $action_url;
    	$this->cl_mid=$mid;
    	if (strlen($key)!=8)
        {
    		echo "<b>TatraPay Error</b>: Key must be 8 chars";
    		exit;
    	}
    	$this->cl_key=$key;
    	if (strlen($image)>0)
    	$this->image_src=$image;
    }


    function set_cs ($new_cs)
    {
        $this->tr_cs=$new_cs;
    }

    function set_vs ($new_vs)
    {
        $this->tr_vs=$new_vs;
    }

    function set_amt ($new_amt)
    {
        $this->tr_amt=$new_amt;
    }

    function set_rurl ($new_rurl)
    {
        $this->tr_rurl=$new_rurl;
    }

    function set_curr ($new_curr = 978)
    {
        $this->tr_curr=$new_curr;
    }


    function set_desc ($new_desc)
    {
        $this->tr_desc=$new_desc;
    }

    function set_rsms ($new_rsms)
    {
        $this->tr_rsms=$new_rsms;
    }

    function set_rem ($new_rem)
    {
        $this->tr_rem=$new_rem;
    }

    function set_lang ($new_lang)
    {
        $this->tr_lang=$new_lang;
    }
    
    function set_ipc ($new_ipc)
    {
    // Toto potrebuje iba cardpay, davame to sem, aby boli volania
    // kompatibilne
    }

    function set_name ($new_name)
    {
    // Toto potrebuje iba cardpay, davame to sem, aby boli volania
    // kompatibilne
    }

    function make_tatra_sign( $value )
    {
        // get the SHA1
        $hash = substr(mhash (MHASH_SHA1, $value), 0, 8);
        $key = $this->cl_key;

        // encrypt hash with key
        if (function_exists('mcrypt_module_open'))
        {         // We have mcrypt 2.4.x
            $td = mcrypt_module_open(MCRYPT_TripleDES, "", MCRYPT_MODE_ECB, "");
            $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size ($td), MCRYPT_RAND);
            mcrypt_generic_init($td, $key, $iv);
            $signature = strtoupper(bin2hex(mcrypt_generic ($td, $hash)));
            mcrypt_generic_deinit ($td);
        }
        else
        {                        // We have 2.2.x only
            $signature = strtoupper(bin2hex(mcrypt_ecb (MCRYPT_3DES, $key, $hash, MCRYPT_ENCRYPT)));
        }

        return $signature;
    }

      /* This function can take 3 or 4 arguments
	function check_reply ($vs, $res, $signature);
	or
	function check_reply ($vs, $res, $ac, $signature);
      */
    function check_reply ()
    {
        $vs = func_get_arg(0);
        $res = func_get_arg(1);
        if (func_num_args()==4)
        {
        $ac = func_get_arg(2); // We also got approval code (used by cardpay)
        }
        else $ac = "";

        $signature = func_get_arg(func_num_args()-1);
        $sign = $this->make_tatra_sign ($vs.$res.$ac);

        if (strcmp($sign, strtoupper ($signature)) == 0) return true;
        else return false;
    }

    function calculate_send_sign ()
    {
        $tosign=$this->cl_mid;
        $tosign.=$this->tr_amt;
        $tosign.=$this->tr_vs;
        $tosign.=$this->tr_cs;
        $tosign.=$this->tr_rurl;

        return ($this->make_tatra_sign ($tosign));
    }


      function generic_form_vars ( )
      {
        $sign = $this->calculate_send_sign();

        $vars='<input type="hidden" name="PT" value="'.$this->action.'">'."\n";
        $vars.='<input type="hidden" name="MID" value="'.$this->cl_mid.'">'."\n";
        $vars.='<input type="hidden" name="AMT" value="'.$this->tr_amt.'">'."\n";
        $vars.='<input type="hidden" name="VS" value="'.$this->tr_vs.'">'."\n";
        $vars.='<input type="hidden" name="CS" value="'.$this->tr_cs.'">'."\n";
        $vars.='<input type="hidden" name="RURL" value="'.$this->tr_rurl.'">'."\n";
        $vars.='<input type="hidden" name="DESC" value="'.$this->tr_desc.'">'."\n";
        $vars.='<input type="hidden" name="RSMS" value="'.$this->tr_rsms.'">'."\n";
        $vars.='<input type="hidden" name="REM" value="'.$this->tr_rem.'">'."\n";
        $vars.='<input type="hidden" name="SIGN" value="'.$sign.'">'."\n";

    	return $vars;
      }

      function generic_pay_form ( $type = "default")
      {

    	// Determine action URL
    	if ($type == 'eliot')
        {
    		$this->action="EliotPay";
    	}
        else if ($type == 'tatra')
        {
    		$this->action="TatraPay";
        }
        else
        {
    		$this->action="";
    	}

    	$form='<!-- tatra banka & eliot ePay form start -->'."\n";
    	$form.='<form action="'.$this->action_url.'" method="POST">'."\n";
    	$form.=$this->generic_form_vars();

    	return $form;
      } // end of generic_pay_form

    function image_pay_form ( $type = "default")
    {
        $form=$this->generic_pay_form($type);
        $form.='<input class="button" type="submit" value="'.JText::_('Zaplatiť').' (TatraPay)" border="0">'."\n";
        $form.='</form>'."\n";
        $form.='<!-- ecommerce form end -->'."\n";

    	return $form;
    }

      function pay_link ( $type = "default") {

	// Determine action URL
	if ($type == 'eliot') {
		$this->action="EliotPay";
	} else if ($type == 'tatra') {
		$this->action="TatraPay";
        } else  {$this->action="";}

	$sign = $this->calculate_send_sign();

	$link=$this->action_url.'&';
	$link.='PT='. urlencode( $this->action ).'&';
	$link.='MID='. urlencode( $this->cl_mid ).'&';
	$link.='AMT='. urlencode( $this->tr_amt ).'&';
	$link.='VS='. urlencode( $this->tr_vs ).'&';
	$link.='CS='. urlencode( $this->tr_cs ).'&';
	$link.='RURL='. urlencode( $this->tr_rurl ).'&';
	$link.='DESC='. urlencode( $this->tr_desc ).'&';
	$link.='RSMS='. urlencode( $this->tr_rsms ).'&';
	$link.='REM='. urlencode( $this->tr_rem ).'&';
	$link.='SIGN='.$sign;

	return $link;

      } // end of pay_link


}

class CardPay extends TatraPay
{
    var $tr_ipc="";	// IP address of the client (can be empty)
    var $tr_name="";	// Name of the client, if known

    var $action_url = "https://moja.tatrabanka.sk/cgi-bin/e-commerce/start/e-commerce.jsp?";
    var $image_src = "/images/cardpay_logo.gif";

    function set_ipc ($new_ipc)
    {
        $this->tr_ipc=$new_ipc;
    }

    function set_name ($new_name)
    {
	   $this->tr_name=$new_name;
    }

    function calculate_send_sign ()
    {
    	$tosign=$this->cl_mid;
    	$tosign.=$this->tr_amt;
    	$tosign.=$this->tr_curr;
    	$tosign.=$this->tr_vs;
    	$tosign.=$this->tr_cs;
    	$tosign.=$this->tr_rurl;
    	$tosign.=$this->tr_ipc;
    	$tosign.=$this->tr_name;

    	return ($this->make_tatra_sign ($tosign));
     }

     function generic_form_vars ()
     {
        $sign = $this->calculate_send_sign();
        $vars ='<input type="hidden" name="PT" value="CardPay">'."\n";
        $vars.='<input type="hidden" name="MID" value="'.$this->cl_mid.'">'."\n";
        $vars.='<input type="hidden" name="AMT" value="'.$this->tr_amt.'">'."\n";
        $vars.='<input type="hidden" name="VS" value="'.$this->tr_vs.'">'."\n";
        $vars.='<input type="hidden" name="CS" value="'.$this->tr_cs.'">'."\n";
        $vars.='<input type="hidden" name="RURL" value="'.$this->tr_rurl.'">'."\n";
        $vars.='<input type="hidden" name="IPC" value="'.$this->tr_ipc.'">'."\n";
        $vars.='<input type="hidden" name="NAME" value="'.$this->tr_name.'">'."\n";
        $vars.='<input type="hidden" name="DESC" value="'.$this->tr_desc.'">'."\n";
        $vars.='<input type="hidden" name="RSMS" value="'.$this->tr_rsms.'">'."\n";
        $vars.='<input type="hidden" name="REM" value="'.$this->tr_rem.'">'."\n";
        $vars.='<input type="hidden" name="LANG" value="'.$this->tr_lang.'">'."\n";
        $vars.='<input type="hidden" name="CURR" value="'.$this->tr_curr.'">'."\n";
        $vars.='<input type="hidden" name="SIGN" value="'.$sign.'">'."\n";

        return $vars;
      }

      function generic_pay_form ( $type = "") { // parameter is ignored in CardPay


	$form='<!-- CardPay form start -->'."\n";
	$form.='<form action="'.$this->action_url.'" method="POST">'."\n";
	$form.=$this->generic_form_vars();

	return $form;

      } // end of generic_pay_form

    function pay_link ( $type = "" )
    {  // parameter is ignored in CardPay
        $sign = $this->calculate_send_sign();

        $link=$this->action_url.'&PT=CardPay&';
        $link.='MID='. urlencode( $this->cl_mid ).'&';
        $link.='AMT='. urlencode( $this->tr_amt ).'&';
        $link.='VS='. urlencode( $this->tr_vs ).'&';
        $link.='CS='. urlencode( $this->tr_cs ).'&';
        $link.='RURL='. urlencode( $this->tr_rurl ).'&';
        $link.='IPC='. urlencode( $this->tr_ipc ).'&';
        $link.='NAME='. urlencode( $this->tr_name ).'&';
        $link.='DESC='. urlencode( $this->tr_desc ).'&';
        $link.='RSMS='. urlencode( $this->tr_rsms ).'&';
        $link.='REM='. urlencode( $this->tr_rem ).'&';
        $link.='LANG='. urlencode( $this->tr_lang ).'&';
        $link.='CURR='. urlencode( $this->tr_curr ).'&';
        $link.='SIGN='.$sign;

        return $link;
    } // end of pay_link

}
?>
