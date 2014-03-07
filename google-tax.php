<?php
/*
Plugin Name: Google Tax
Plugin URI: http://fontethemes.com
Description: Replace the URL base of the communication media who are members of CEDRO and AEDE
Version: 0.1.0
Author: Jesus Amieiro
Author URI: http://fontethemes.com
License: GPL2
*/
/*
Copyright 2014  Jesus Amieiro  (email : info@fontethemes.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

	$url_base = get_bloginfo('url');
	$domain_base = str_replace("http://","",get_bloginfo('url'));
	$domain_base = str_replace("https://","",$domain_base);
	$reg_exUrl = "!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?&_/=\-]+!"; 
	$reg_exDomain = "/[\w\d\-\.]+\.[\w\d]{1,3}/";

	$domain_list = array (	'abc.es',
							'abcdesevilla.es',
							'aede.es',
							'as.com',
							'canarias7.es',
							'cincodias.com',
							'deia.com',
							'diaridegirona.cat',
							'diaridetarragona.com',
							'diarideterrassa.es',
							'diariocordoba.com',
							'diariodeavila.es',
							'diariodeavisos.com',
							'diariodeburgos.es',
							'diariodecadiz.es',
							'diariodeibiza.es',
							'diariodejerez.es',
							'diariodelaltoaragon.es',
							'diariodeleon.es',
							'diariodemallorca.es',
							'diariodenavarra.es',
							'diariodesevilla.es',
							'diarioinformacion.com',
							'diariojaen.es',
							'diariopalentino.es',
							'diariovasco.com',
							'eladelantado.com',
							'elcomercio.es',
							'elcorreo.com',
							'elcorreoweb.es',
							'eldiadecordoba.es',
							'eldiariomontanes.es',
							'eleconomista.es',
							'elmundo.es',
							'elpais.com',
							'elpais.es',
							'elperiodico.com',
							'elperiodicodearagon.com',
							'elperiodicoextremadura.com',
							'elperiodicomediterraneo.com',
							'elprogreso.es',
							'elprogreso.galiciae.com',
							'europasur.es',
							'expansion.com',
							'farodevigo.es',
							'gaceta.es',
							'granadahoy.com',
							'heraldo.es',
							'heraldodesoria.es',
							'hoy.es',
							'huelvainformacion.es',
							'ideal.es',
							'intereconomia.com',
							'lagacetadesalamanca.es',
							'laopinion.es',
							'laopinioncoruna.es',
							'laopiniondemalaga.es',
							'laopiniondemurcia.es',
							'laopiniondezamora.es',
							'laprovincia.es',
							'larazon.es',
							'larioja.com',
							'lasprovincias.es',
							'latribunadeciudadreal.es',
							'latribunadetalavera.es',
							'latribunadetoledo.es',
							'lavanguardia.com',
							'laverdad.es',
							'lavozdealmeria.es',
							'lavozdegalicia.es',
							'lavozdigital.es',
							'levante-emv.com',
							'lne.es',
							'majorcadailybulletin.es',
							'majorcadailybulletin.com',
							'malagahoy.es',
							'marca.com',
							'mundodeportivo.com',
							'noticiasdealava.com',
							'noticiasdegipuzkoa.com',
							'regio7.cat',
							'sport.es',
							'superdeporte.es',
							'ultimahora.es',
						);		
//print_r($domain_list);
// Hooks list http://codex.wordpress.org/Plugin_API/Filter_Reference
add_filter('the_content', 'remove_links');
add_filter('the_title', 'remove_links');
///////////////add_filter('the_permalink', 'remove_links');



function remove_links($content) {
	// Remove the URL: http|ftp|scp)(s)
	preg_match_all($GLOBALS['reg_exUrl'], $content, $matches);
	foreach ($matches[0] as $pattern) {
			if (strposa($pattern,$GLOBALS['domain_list'])) {
				$content = str_replace_first($pattern, $GLOBALS['url_base'],$content);
			}
	}
	// Remove the remain domains
	preg_match_all($GLOBALS['reg_exDomain'], $content, $matches);
    foreach ($matches[0] as $pattern) {
			if (strposa($pattern,$GLOBALS['domain_list'])) {
	 	        $content = str_replace_first($pattern, $GLOBALS['domain_base'],$content);
            }
    }
	return $content;
}

function strposa($haystack, $needle, $offset=0) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $query) {
        if(stripos($haystack, $query, $offset) !== false) return true; // stop on first true result
    }
    return false;
}

function str_replace_first($search, $replace, $subject) {
    return implode($replace, explode($search, $subject, 2));
}
