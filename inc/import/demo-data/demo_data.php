<?php

$demo_menus = array(
	'primary' 	=> __( 'Primary Menu', 'patch' ),
	'social' 	=> __( 'Social Menu', 'patch' ),
	'footer'    => __( 'Footer Menu', 'patch' ),
);

/**
 * Use this plugin to export WordPress options https://wordpress.org/plugins/options-importer/
 * Steps:
 * 1 - Export the demo options
 * 2 - From the exported file get only the options array and encode it http://vps5.cgwizz.com/encoder/
 * 3 - After that paste the encoded string here:
 */
$theme_options = "ZmFsc2U=";

//The export of the widgets using this plugin http://wordpress.org/plugins/widget-settings-importexport/ - base64 encoded: http://www.base64encode.org/
$demo_widgets = 'W3sic2lkZWJhci0xIjpbInRleHQtMiIsImJsb2dfc3Vic2NyaXB0aW9uLTIiXX0seyJ0ZXh0Ijp7IjIiOnsidGl0bGUiOiIiLCJ0ZXh0IjoiQW4gYWxnb3JpdGhtaWMgZGVzaWduIGV4cGVyaW1lbnQgdGhhdCBjbGV2ZXJseSBpbWFnaW5lcyB0aGUgYmVzdCBsYXlvdXQgZm9yIHlvdXIgY29udGVudC4gIiwiZmlsdGVyIjpmYWxzZX0sIl9tdWx0aXdpZGdldCI6MX0sImJsb2dfc3Vic2NyaXB0aW9uIjp7IjIiOnsidGl0bGUiOiJTdWJzY3JpYmUgdG8gQmxvZyB2aWEgRW1haWwiLCJzdWJzY3JpYmVfdGV4dCI6IkVudGVyIHlvdXIgZW1haWwgYWRkcmVzcyB0byBzdWJzY3JpYmUgdG8gdGhpcyBibG9nIGFuZCByZWNlaXZlIG5vdGlmaWNhdGlvbnMgb2YgbmV3IHBvc3RzIGJ5IGVtYWlsLiIsInN1YnNjcmliZV9wbGFjZWhvbGRlciI6IkVtYWlsIEFkZHJlc3MiLCJzdWJzY3JpYmVfYnV0dG9uIjoiU3Vic2NyaWJlIiwic3VjY2Vzc19tZXNzYWdlIjoiU3VjY2VzcyEgQW4gZW1haWwgd2FzIGp1c3Qgc2VudCB0byBjb25maXJtIHlvdXIgc3Vic2NyaXB0aW9uLiBQbGVhc2UgZmluZCB0aGUgZW1haWwgbm93IGFuZCBjbGljayBhY3RpdmF0ZSB0byBzdGFydCBzdWJzY3JpYmluZy4iLCJzaG93X3N1YnNjcmliZXJzX3RvdGFsIjpmYWxzZX0sIl9tdWx0aXdpZGdldCI6MX19XQ==';