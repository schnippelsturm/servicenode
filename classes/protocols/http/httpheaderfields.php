<?php

namespace ServiceNode\protocol\http;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*
 *
  Content-Type 	14.17 	Der MIME-Typ der angeforderten Datei.
 *                   Er kann nicht mit einer Charset Angabe im HTML header �berschrieben werden. 	Content-Type: text/html; charset=utf-8
  Date 	14.18 	Zeitpunkt des Absendens 	Date: Tue, 15 Nov 1994 08:12:31 GMT
  ETag 	14.19 	Eine bestimmte Version einer Datei, oft als Message Digest realisiert 	ETag: "737060cd8c284d8af7ad3082f209582d"
  Expires 	14.21 	Ab wann die Datei als veraltet angesehen werden kann 	Expires: Thu, 01 Dec 1994 16:00:00 GMT
  Last-Modified 	14.29 	Zeitpunkt der letzten �nderung an der Datei (als RFC 2822) 	Last-Modified: Tue, 15 Nov 1994 12:45:26 GMT
  Link 	RFC 5988 Abschn. 5 	Wird benutzt, um dem Client ?verwandte? Dateien oder Ressourcen mitzuteilen, z. B. einen RSS-Feed, einen Favicon, Copyright-Lizenzen etc. Dieses Header-Feld ist �quivalent zum <link />-Feld in (X)HTML.[4] 	Link: </feed>; rel="alternate"
  Location 	14.30 	Oft genutzt, um Clients weiterzuleiten (mit einem 3xx-Code) 	Location: http://www.w3.org/pub/WWW/People.html
  P3P 	- 	Dieses Feld wird genutzt, um eine P3P-Datenschutz-Policy wie folgt mitzuteilen:P3P:CP="your_compact_policy". P3P setzte sich nicht richtig durch,[5] wird jedoch von einigen Browsern und Webseiten genutzt, um z. B. Cookie-Richtlinien durchzusetzen oder zu �berpr�fen. 	P3P: CP="This is not a P3P policy! See http://www.google.com/support/accounts/bin/answer.py?hl=en&answer=151657 for more info."
  Pragma 	14.32 	Implementierungs-spezifische Optionen, die mehrere Stationen in der Request-Response-Kette beeinflussen k�nnen. 	Pragma: no-cache
  Proxy-Authenticate 	14.33 	Anweisung, ob und wie der Client sich beim Proxy zu authentifizieren hat 	Proxy-Authenticate: Basic
  Refresh 	Propriet�r 	Refresh wird genutzt, um nach einer bestimmten Zahl von Sekunden weiterzuleiten oder die Seite zu aktualisieren. Dieses Headerfeld ist propriet�r und kommt von Netscape, wird aber von den meisten Browsern unterst�tzt 	Refresh: 5; url=http://www.w3.org/pub/WWW/People.html
  Retry-After 	14.37 	Falls eine Ressource zeitweise nicht verf�gbar ist, so teilt der Server dem Client mit diesem Feld mit, wann sich ein neuer Versuch lohnt. 	Retry-After: 120
  Server 	14.38 	Serverkennung (so wie User-Agent f�r den Client ist, ist Server f�r die Serversoftware) 	Server: Apache/1.3.27 (Unix) (Red-Hat/Linux)
  Set-Cookie 	- 	Ein Cookie 	Set-Cookie: UserID=FooBar; Max-Age=3600; Version=1
  Trailer 	14.40 	Das Trailer-Feld enth�lt die Namen der Headerfelder, die im Trailer der Antwort (bei Chunked-Encoding) enthalten sind. Eine Nachricht in Chunked-Encoding ist aufgeteilt in den Header (Kopf), den Rumpf (Body) und den Trailer, wobei der Rumpf aus Effizienzgr�nden in Teile (Chunks) aufgeteilt sein kann. Der Trailer kann dann (je nach Wert des TE-Felders der Anfrage) Header-Informationen beinhalten, deren Vorabberechnung der Effizienzsteigerung zuwiderl�uft. 	Trailer: Max-Forwards
  Transfer-Encoding 	14.41 	Die Methode, die genutzt wird, den Inhalt sicher zum Nutzer zu bringen. Zurzeit sind folgende Methoden definiert: chunked (aufgeteilt), compress (komprimiert), deflate (komprimiert), gzip (komprimiert), identity. 	Transfer-Encoding: chunked
  Vary 	14.44 	Zeigt Downstream-Proxys, wie sie anhand der Headerfelder zuk�nftige Anfragen behandeln sollen, also ob die gecachte Antwort genutzt werden kann oder eine neue Anfrage gestellt werden soll. 	Vary: *
  Via 	14.45 	Informiert den Client, �ber welche Proxys die Antwort gesendet wurde. 	Via: 1.0 fred, 1.1 nowhere.com (Apache/1.1)
  Warning 	14.46 	Eine allgemeine Warnung vor Problemen mit dem Body 	Warning: 199 Miscellaneous warning
  WWW-Authenticate 	14.47 	Definiert die Authentifikationsmethode, die genutzt werden soll, um eine bestimmte Datei herunterzuladen (Genauer definiert in RFC 2617)
 */

$Responseheaderfields = array(
    'Accept-Ranges', 'Age', 'Allow', 'Cache-Control', 'Connection', 'Content-Type',
    'Content-Encoding', 'Content-Language', 'Content-Length', 'Content-Location',
    'Content-MD5', 'Content-Disposition', 'Content-Range', 'Content-Security-Policy',
    'Date', 'ETag', 'Expires', 'Last-Modified', 'Link', 'Location', 'P3P', 'Pragma',
    'Proxy-Authenticate', 'Refresh', 'Retry-After', 'Server', 'Set-Cookie', 'Trailer',
    'Transfer-Encoding', 'Vary', 'Via', 'Warning', 'WWW-Authenticate'
);

$Requestheaderfields = array(
    'Accept', 'Accept-Charset', 'Accept-Encoding', 'Accept-Language',
    'Authorization', 'Cache-Control', 'Connection', 'Cookie', 'Content-Length',
    'Content-MD5', 'Content-Type', 'Date', 'Expect', 'From', 'Host',
    'If-Match', 'If-Modified-Since', 'If-None-Match', 'If-Range', 'If-Unmodified-Since',
    'Max-Forwards', 'Pragma', 'Proxy-Authorization', 'Range', 'Referer',
    'TE', 'Transfer-Encoding', 'Upgrade', 'User-Agent', 'Via', 'Warning'
);
?>
