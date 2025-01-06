<?php

	return [
		'restriction' => 1, 				 // 0 for no restriction, 1 for country wise, 2 for region wise
	    'allowed_countries' => ['IN','CA'], //add country code as CA for Canada or GB for United Kingdom
	    'blocked_countries' => ['CN'], 		// add country code if you want to restrict any countries to not use.
	    'allowed_regions' => ['GJ', 'QC'], 	// if you add restriction 2 then add region code to allow
	    'blocked_regions' => ['GD'], // if you add restriction 2 then add region code to block users region wise.
	    'error_message' => 'Access denied due to geographical restrictions.',	// give an error message for restricted zones.
	];