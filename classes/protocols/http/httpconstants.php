<?php
namespace ServiceNode\protocol\http;

if (!defined(__NAMESPACE__ .'\HTTP_METHOD_HEAD'))   
		{ define(__NAMESPACE__ .'\HTTP_METHOD_HEAD', 'HEAD');        }
if (!defined(__NAMESPACE__ .'\HTTP_METHOD_GET'))  
		{ define(__NAMESPACE__ .'\HTTP_METHOD_GET', 'GET');          }
if (!defined(__NAMESPACE__ .'\HTTP_METHOD_POST'))   
		{ define(__NAMESPACE__ .'\HTTP_METHOD_POST', 'POST');        }
if (!defined(__NAMESPACE__ .'\HTTP_METHOD_PUT'))   
		{ define(__NAMESPACE__ .'\HTTP_METHOD_PUT', 'PUT');          }
if (!defined(__NAMESPACE__ .'\HTTP_METHOD_DELETE')) 
		{ define(__NAMESPACE__ .'\HTTP_METHOD_DELETE', 'DELETE');    }
if (!defined(__NAMESPACE__ .'\HTTP_METHOD_CONNECT')) 
		 { define(__NAMESPACE__ .'\HTTP_METHOD_CONNECT', 'CONNECT'); }
if (!defined(__NAMESPACE__ .'\HTTP_METHOD_TRACE'))   
		  { define(__NAMESPACE__ .'\HTTP_METHOD_TRACE', 'TRACE');     }
if (!defined(__NAMESPACE__ .'\HTTP_METHOD_OPTIONS')) 
		  { define(__NAMESPACE__ .'\HTTP_METHOD_OPTIONS', 'OPTIONS'); }
?>
