<?php

class XmlParserComponent extends Object {
	
	function parse_string()
	{
	  $file = "laws/231 01 responsabilità amministrativa.xml";
	  
	  $xml = new DomDocument();
	  $xml->load($file);
	  
	  // Preleva il tag "DecretoLegislativo" (eventualmente si dovranno gestire gli altri tipi)
	  // NOTA: getElementsByTagName() ritorna una specie di lista, quindi si deve selezionare
	  // solo il primo (nonchè unico) elemento.
	  $law = $xml->documentElement->getElementsByTagName("DecretoLegislativo")->item(0);
	  
	  // Array per le stringhe da ritornare
	  $strings = array();
	  
	  // Inizializza le stringhe.
	  $head_string = "";
	  $form_in_string = "";
	  $art_string = "";
	  
	  // Inizio parte principale
	  foreach($law->childNodes as $child)
	  {
	    switch($child->nodeName)
	    {
	      case "intestazione":
	        foreach($child->childNodes as $nephew)
	        {
	          switch($nephew->nodeName)
	          {
	            case "tipoDoc":
	              $head_string .= $nephew->nodeValue;
	              break;
	            
	            case "dataDoc":
	              $head_string .= $nephew->nodeValue;
	              break;
	            
	            case "numDoc":
	              $head_string .= $nephew->nodeValue."<br />";
	              break;
	            
	            case "titoloDoc":
	              $head_string .= $nephew->nodeValue."<br />";
	              break;
	            
	            // Questo default particolare serve a sopperire alla presenza di testo
	            // sia nel tag padre ("intestazione") che nei tag figli ("tipoDoc", "dataDoc",
	            // ecc...) che fortunatamente DOM vede come figli distinti (e quindi isolabili)
	            // del tag padre.
	            // NOTA: ltrim qui sotto ovvia alla presenza di uno spazio di troppo tra la
	            // prima parte del testo di "intestazione" (quella tra "dataDoc" e "numDoc",
	            // vedi xml della legge all'inizio), che causava la stampa della stringa
	            // "30 giugno 2003 , n. 196" (cioè con uno spazio di troppo prima della virgola).
	            default:
	              $head_string .= ltrim($nephew->nodeValue);
	              break;
	          }
	        }
	        break;
	
	      case "formulainiziale":
	        foreach($child->childNodes as $nephew)
	        {
	          if($nephew->nodeName == "preambolo")
	          {
	            // Qui sfrutto due proprietà differenti per selezionare solo i
	            // tag "h:p" (che hanno un namespace); il prefisso viene
	            // memorizzato in "prefix", mentre il nome locale in "localName".
	            foreach($nephew->childNodes as $hp)
	            {
	              if(($hp->prefix == "h")&&($hp->localName == "p"))
	              {
	                $form_in_string .= "<br />".$hp->nodeValue;
	              }
	            }
	            $form_in_string .= "<br />";
	          }
	          else if(($nephew->prefix == "h")&&($nephew->localName == "p"))
	          {
	            $form_in_string .= $nephew->nodeValue;
	          }
	        }
	        break;
	      
	      case "articolato":
	        $capi = $child->getElementsByTagName("capo");
	        for($i = 0; $i < $capi->length; $i++)
	        {
	          foreach($capi->item($i)->childNodes as $subCapo)
	          {
	            switch ($subCapo->nodeName)
	            {
	              case "num":
	                $art_string .= $subCapo->nodeValue."<br />";
	                break;
	              
	              case "rubrica":
	                $art_string .= $subCapo->nodeValue."<br />";
	                break;
	              
	              case "sezione":
	                // NOTA: stesso discorso di sopra, anche se si parla di "$title_child" sono in realtà
	                //       i figli delle sezioni...
	                foreach($subCapo->childNodes as $section_child)
	                {
	                  switch($section_child->nodeName)
	                  {
	                    case "num":
	                      $art_string .= $section_child->nodeValue."<br />";
	                      break;
	
	                    case "rubrica":
	                      $art_string .= $section_child->nodeValue."<br />";
	                      break;
	                    
	                    case "articolo":
	                      foreach($section_child->childNodes as $art_child)
	                      { 
	                        switch($art_child->nodeName)
	                        {
	                          case "num":
	                            $art_string .= $art_child->nodeValue;
	                            break;
	                          
	                          case "rubrica":
	                            $art_string .= $art_child->nodeValue."<br />";
	                            break;
	                          
	                          case "comma":
	                            foreach($art_child->childNodes as $comma_child)
	                            {
	                              switch($comma_child->nodeName)
	                              {
	                                case "num":
	                                  $art_string .= $comma_child->nodeValue." ";
	                                  break;
	                                
	                                case "corpo":
	                                  $art_string .= $comma_child->nodeValue."<br />";
	                                  break;
	                                
	                                case "alinea":
	                                  $art_string .= $comma_child->nodeValue."<br />";
	                                  break;
	                                
	                                case "el":
	                                  foreach($comma_child->childNodes as $el_child)
	                                  {
	                                    switch($el_child->nodeName)
	                                    {
	                                      case "num":
	                                        $art_string .= $el_child->nodeValue." ";
	                                        break;
	                                      
	                                      case "corpo":
	                                        $art_string .= $el_child->nodeValue."<br />";
	                                        break;
	                                      
	                                      default:
	                                        break;
	                                    }
	                                  }
	                                  break;
	                                
	                                default:
	                                  break;
	                              }
	                            }
	                            break;
	                          
	                          default:
	                            break;
	                        }
	                      }
	                      //$art_string .= "<br />";
	                      break;
	                    
	                    default:
	                      break;
	                  }
	                }
	                break;
	              
	              case "articolo":
	                foreach($subCapo->childNodes as $art_child)
	                { 
	                  switch($art_child->nodeName)
	                  {
	                    case "num":
	                      $art_string .= $art_child->nodeValue;
	                      break;
	                    
	                    case "rubrica":
	                      $art_string .= $art_child->nodeValue."<br />";
	                      break;
	                    
	                    case "comma":
	                      foreach($art_child->childNodes as $comma_child)
	                      {
	                        switch($comma_child->nodeName)
	                        {
	                          case "num":
	                            $art_string .= $comma_child->nodeValue." ";
	                            break;
	                          
	                          case "corpo":
	                            $art_string .= $comma_child->nodeValue."<br />";
	                            break;
	                          
	                          case "alinea":
	                            $art_string .= $comma_child->nodeValue."<br />";
	                            break;
	                          
	                          case "el":
	                            foreach($comma_child->childNodes as $el_child)
	                            {
	                              switch($el_child->nodeName)
	                              {
	                                case "num":
	                                  $art_string .= $el_child->nodeValue." ";
	                                  break;
	                                
	                                case "corpo":
	                                  $art_string .= $el_child->nodeValue."<br />";
	                                  break;
	                                
	                                default:
	                                  break;
	                              }
	                            }
	                            break;
	                          
	                          default:
	                            break;
	                        }
	                      }
	                      break;
	                    
	                    default:
	                      break;
	                  }
	                }
	                break;
	              
	              default:
	                break;
	            }
	          }
	        }
	        break;
	      
	      case "formulafinale":
	        foreach($child->childNodes as $nephew)
	        {
	          if(($nephew->prefix == "h")&&($nephew->localName == "p"))
	          {
	            $form_fin_string .= "<br />".$nephew->nodeValue;
	          }
	        }
	        break;
	
	      case "conclusione":
	        foreach($child->childNodes as $nephew)
	        {
	          switch($nephew->nodeName)
	          {
	            case "firma":
	              $concl_string .= $nephew->nodeValue."<br />";
	              break;
	              
	            default:
	              break;
	          }
	        }
	        break;
	        
	      default:
	        break;
	        
	      default:
	        break;
	
	    }
	  }
	  $strings["intestazione"] = $head_string;
	  $strings["formulainiziale"] = $form_in_string;
	  $strings["articolato"] = $art_string;
	  
	  return ($strings);
	}
	
	function parse_count()
	{
	  $file = "laws/231 01 responsabilità amministrativa.xml";
	  
	  $xml = new DomDocument();
	  $xml->load($file);
	  
	  // Preleva il tag "DecretoLegislativo" (eventualmente si dovranno gestire gli altri tipi)
	  // NOTA: getElementsByTagName() ritorna una specie di lista, quindi si deve selezionare
	  // solo il primo (nonchè unico) elemento.
	  $law = $xml->documentElement->getElementsByTagName("DecretoLegislativo")->item(0);
	  
	  // Array per i contatori da ritornare
	  $counters = array();
	  
	  // Inizializza i contatori.
	  $capo_count = 0;
	  $section_count = 0;
	  $art_count = 0;
	  $comma_count = 0;
	  
	  foreach($law->childNodes as $child)
	  {
	    switch($child->nodeName)
	    {
	      case "articolato":
	        $capi = $child->getElementsByTagName("capo");
	        for($i = 0; $i < $capi->length; $i++)
	        {
	          $capo_count++;
	          foreach($capi->item($i)->childNodes as $subCapo)
	          {
	            switch ($subCapo->nodeName)
	            {
	              case "sezione":
	              $section_count++;
	                foreach($subCapo->childNodes as $section_child)
	                {
	                  switch($section_child->nodeName)
	                  {
	                    case "articolo":
	                      $art_count++;
	                      foreach($section_child->childNodes as $art_child)
	                      { 
	                        switch($art_child->nodeName)
	                        {
	                          case "comma":
	                            $comma_count++;
	                            break;
	                          
	                          default:
	                            break;
	                        }
	                      }
	                      break;
	                    
	                    default:
	                      break;
	                  }
	                }
	                break;
	              
	              case "articolo":
	                $art_count++;
	                foreach($subCapo->childNodes as $art_child)
	                { 
	                  switch($art_child->nodeName)
	                  {
	                    case "comma":
	                      $comma_count++;
	                      break;
	                    
	                    default:
	                      break;
	                  }
	                }
	                break;
	              
	              default:
	                break;
	            }
	          }
	        }
	        break;
	        
	      default:
	        break;
	    }
	  }
	  $counters["capi"] = $capo_count;
	  $counters["sezioni"] = $section_count;
	  $counters["articoli"] = $art_count;
	  $counters["commi"] = $comma_count;
	  
	  return ($counters);
	}
	
	function parse_json()
	{
	  $file = "laws/231 01 responsabilità amministrativa.xml";
	  
	  $xml = new DomDocument();
	  $xml->load($file);
	  
	  // Preleva il tag "DecretoLegislativo" (eventualmente si dovranno gestire gli altri tipi)
	  // NOTA: getElementsByTagName() ritorna una specie di lista, quindi si deve selezionare
	  // solo il primo (nonchè unico) elemento.
	  $law = $xml->documentElement->getElementsByTagName("DecretoLegislativo")->item(0);
	  
	  // Contenitore per il json da ritornare
	  $json;
	  
	  // Inizializza i contatori (solo per parti, e articoli - titoli, capi e commi hanno contatori locali).
	  $main_capo_count = 0;
	  $main_art_count = 0;
	  
	  // Variabili per il json
	  $json_array = array(); // Corrispondente a "DecretoLegislativo"
	  
	  $json_array["identifier"] = "id";
	  $json_array["label"] = "name";
	  $json_array["items"] = array();
	  
	  // Variabili che contengono l'ultimo indice utilizzato negli array per il json di ciascuna categoria
	  $json_capo_index;
	  $json_section_index;
	  $json_art_index;
	  
	  foreach($law->childNodes as $child)
	  {
	    switch($child->nodeName)
	    {
	      case "articolato":
	        $capi = $child->getElementsByTagName("capo");
	        for($i = 0; $i < $capi->length; $i++)
	        {
	          $main_capo_count++;
	          $local_section_count = 0;
	          
	          $capo_id = $capi->item($i)->getAttribute("id");
	        
	          $json_capo_array = array();
	          $json_capo_array["id"] = $capo_id;
	          $json_capo_array["type"] = "capo";
	          $json_capo_array["name"] = "Capo ".$main_capo_count;
	          $json_capo_array["children"] = array();
	          
	          $json_array["items"][] = $json_capo_array;
	          
	          $json_capo_index = count($json_array["items"]) - 1;
	          
	          foreach($capi->item($i)->childNodes as $subCapo)
	          {
	            switch ($subCapo->nodeName)
	            {
	              case "sezione":
	                $local_section_count++;
	                
	                $section_id = $subCapo->getAttribute("id");
	                
	                $json_section_array = array();
	                $json_section_array["id"] = $section_id;
	                $json_section_array["type"] = "sezione";
	                $json_section_array["name"] = "Sezione ".$local_section_count;
	                $json_section_array["children"] = array();
	                
	                $json_array["items"][$json_capo_index]["children"][] = array("_reference" => $section_id);
	                $json_array["items"][] = $json_section_array;
	                
	                $json_section_index = count($json_array["items"]) - 1;
	                
	                foreach($subCapo->childNodes as $section_child)
	                {
	                  switch($section_child->nodeName)
	                  {
	                    case "articolo":
	                      $main_art_count++;
	                      $local_comma_count = 0;
	                    
	                      $art_id = $section_child->getAttribute("id");
	                    
	                      $json_art_array = array();
	                      $json_art_array["id"] = $art_id;
	                      $json_art_array["type"] = "articolo";
	                      $json_art_array["name"] = "Articolo ".$main_art_count;
	                      $json_art_array["children"] = array();
	                      
	                      $json_array["items"][$json_section_index]["children"][] = array("_reference" => $art_id);
	                      $json_array["items"][] = $json_art_array;
	                      
	                      $json_art_index = count($json_array["items"]) - 1;
	                      
	                      foreach($section_child->childNodes as $art_child)
	                      { 
	                        switch($art_child->nodeName)
	                        {
	                          case "comma":
	                            $local_comma_count++;
	                          
	                            $comma_id = $art_child->getAttribute("id");
	                            
	                            $json_comma_array = array();
	                            $json_comma_array["id"] = $comma_id;
	                            $json_comma_array["type"] = "comma";
	                            $json_comma_array["name"] = "Comma ".$local_comma_count;
	                            
	                            $json_array["items"][$json_art_index]["children"][] = array("_reference" => $comma_id);
	                            $json_array["items"][] = $json_comma_array;
	                            break;
	                          
	                          default:
	                            break;
	                        }
	                      }
	                      break;
	                    
	                    default:
	                      break;
	                  }
	                }
	                break;
	              
	              case "articolo":
	                $main_art_count++;
	                $local_comma_count = 0;
	              
	                $art_id = $subCapo->getAttribute("id");
	              
	                $json_art_array = array();
	                $json_art_array["id"] = $art_id;
	                $json_art_array["type"] = "articolo";
	                $json_art_array["name"] = "Articolo ".$main_art_count;
	                $json_art_array["children"] = array();
	                
	                $json_array["items"][$json_capo_index]["children"][] = array("_reference" => $art_id);
	                $json_array["items"][] = $json_art_array;
	                
	                $json_art_index = count($json_array["items"]) - 1;
	                
	                foreach($subCapo->childNodes as $art_child)
	                { 
	                  switch($art_child->nodeName)
	                  {
	                    case "comma":
	                      $local_comma_count++;
	                    
	                      $comma_id = $art_child->getAttribute("id");
	                      
	                      $json_comma_array = array();
	                      $json_comma_array["id"] = $comma_id;
	                      $json_comma_array["type"] = "comma";
	                      $json_comma_array["name"] = "Comma ".$local_comma_count;
	                      
	                      $json_array["items"][$json_art_index]["children"][] = array("_reference" => $comma_id);
	                      $json_array["items"][] = $json_comma_array;
	                      break;
	                    
	                    default:
	                      break;
	                  }
	                }
	                break;
	              
	              default:
	                break;
	            }
	          }
	        }
	        break;
	      
	      default:
	        break;
	
	    }
	  }
	  $json = json_encode($json_array);
	  
	  return ($json);
	}
	
	function parse_html()
	{
	  $file = "laws/231 01 responsabilità amministrativa.xml";
	  
	  $xml = new DomDocument();
	  $xml->load($file);
	  
	  // Preleva il tag "DecretoLegislativo" (eventualmente si dovranno gestire gli altri tipi)
	  // NOTA: getElementsByTagName() ritorna una specie di lista, quindi si deve selezionare
	  // solo il primo (nonchè unico) elemento.
	  $law = $xml->documentElement->getElementsByTagName("DecretoLegislativo")->item(0);
	  
	  // Inizializza le stringhe per la stampa a video.
	  $html_string = "<div><a name = 'top'></a>";
	  
	  foreach($law->childNodes as $child)
	  {
	    switch($child->nodeName)
	    {
	      case "intestazione":
	        $html_string .= "<div id = 'intestazione'><a name = 'intestazione'></a>";
	        
	        foreach($child->childNodes as $nephew)
	        {
	          switch($nephew->nodeName)
	          {
	            case "tipoDoc":
	              $html_string .= $nephew->nodeValue;
	              break;
	            
	            case "dataDoc":
	              $html_string .= $nephew->nodeValue;
	              break;
	            
	            case "numDoc":
	              $html_string .= $nephew->nodeValue."<br />";
	              break;
	            
	            case "titoloDoc":
	              $html_string .= $nephew->nodeValue."<br />";
	              break;
	            
	            // Questo default particolare serve a sopperire alla presenza di testo
	            // sia nel tag padre ("intestazione") che nei tag figli ("tipoDoc", "dataDoc",
	            // ecc...) che fortunatamente DOM vede come figli distinti (e quindi isolabili)
	            // del tag padre.
	            // NOTA: ltrim qui sotto ovvia alla presenza di uno spazio di troppo tra la
	            // prima parte del testo di "intestazione" (quella tra "dataDoc" e "numDoc",
	            // vedi xml della legge all'inizio), che causava la stampa della stringa
	            // "30 giugno 2003 , n. 196" (cioè con uno spazio di troppo prima della virgola).
	            default:
	              $html_string .= ltrim($nephew->nodeValue);
	              break;
	          }
	        }
	        
	        $html_string .= "</div><br />";
	        break;
	
	      case "formulainiziale":
	        $html_string .= "<div id = 'formulainiziale'><a name = 'formulainiziale'></a>";
	        
	        foreach($child->childNodes as $nephew)
	        {
	          if($nephew->nodeName == "preambolo")
	          {
	            // Qui sfrutto due proprietà differenti per selezionare solo i
	            // tag "h:p" (che hanno un namespace); il prefisso viene
	            // memorizzato in "prefix", mentre il nome locale in "localName".
	            foreach($nephew->childNodes as $hp)
	            {
	              if(($hp->prefix == "h")&&($hp->localName == "p"))
	              {
	                $html_string .= "<br />".$hp->nodeValue;
	              }
	            }
	            $html_string .= "<br />";
	          }
	          else if(($nephew->prefix == "h")&&($nephew->localName == "p"))
	          {
	            $html_string .= $nephew->nodeValue;
	          }
	        }
	        
	        $html_string .= "</div><br />";
	        break;
	      
	      case "articolato":
	        $html_string .= "<div id = 'articolato'>";
	      
	        $capi = $child->getElementsByTagName("capo");
	        
	        for($i = 0; $i < $capi->length; $i++)
	        {
	          $capo_id = $capi->item($i)->getAttribute("id");
	          
	          $html_string .= "<div id = '".$capo_id."'><a name = '".$capo_id."'></a>";
	        
	          foreach($capi->item($i)->childNodes as $subCapo)
	          {
	            switch ($subCapo->nodeName)
	            {
	              case "num":
	                $html_string .= $subCapo->nodeValue."<br />";
	                break;
	              
	              case "rubrica":
	                $html_string .= $subCapo->nodeValue."<br />";
	                break;
	              
	              case "sezione":
	                $section_id = $subCapo->getAttribute("id");
	                
	                $html_string .= "<div id = '".$section_id."'><a name = '".$section_id."'></a>";
	                
	                foreach($subCapo->childNodes as $section_child)
	                {
	                  switch($section_child->nodeName)
	                  {
	                    case "num":
	                      $html_string .= $section_child->nodeValue."<br />";
	                      break;
	
	                    case "rubrica":
	                      $html_string .= $section_child->nodeValue."<br />";
	                      break;
	                    
	                    case "articolo":
	                      $art_id = $section_child->getAttribute("id");
	                      
	                      $html_string .= "<div id = '".$art_id."'><a name = '".$art_id."'></a>";
	                    
	                      foreach($section_child->childNodes as $art_child)
	                      { 
	                        switch($art_child->nodeName)
	                        {
	                          case "num":
	                            $html_string .= $art_child->nodeValue;
	                            break;
	                          
	                          case "rubrica":
	                            $html_string .= $art_child->nodeValue."<br />";
	                            break;
	                          
	                          case "comma":
	                            $comma_id = $art_child->getAttribute("id");
	                            
	                            $html_string .= "<div id = '".$comma_id."'><a name = '".$comma_id."'></a>";
	                            
	                            foreach($art_child->childNodes as $comma_child)
	                            {
	                              switch($comma_child->nodeName)
	                              {
	                                case "num":
	                                  $html_string .= $comma_child->nodeValue." ";
	                                  break;
	                                
	                                case "corpo":
	                                  $html_string .= $comma_child->nodeValue."<br />";
	                                  break;
	                                
	                                case "alinea":
	                                  $html_string .= $comma_child->nodeValue."<br />";
	                                  break;
	                                
	                                case "el":
	                                  foreach($comma_child->childNodes as $el_child)
	                                  {
	                                    switch($el_child->nodeName)
	                                    {
	                                      case "num":
	                                        $html_string .= $el_child->nodeValue." ";
	                                        break;
	                                      
	                                      case "corpo":
	                                        $html_string .= $el_child->nodeValue."<br />";
	                                        break;
	                                      
	                                      default:
	                                        break;
	                                    }
	                                  }
	                                  break;
	                                
	                                default:
	                                  break;
	                              }
	                            }
	                            
	                            $html_string .= "</div>";
	                            break;
	                          
	                          default:
	                            break;
	                        }
	                      }
	                      
	                      $html_string .= "</div>";
	                      break;
	                    
	                    default:
	                      break;
	                  }
	                }
	                
	                $html_string .= "</div>";
	                break;
	                
	                
	              case "articolo":
	                $art_id = $subCapo->getAttribute("id");
	                
	                $html_string .= "<div id = '".$art_id."'><a name = '".$art_id."'></a>";
	              
	                foreach($subCapo->childNodes as $art_child)
	                { 
	                  switch($art_child->nodeName)
	                  {
	                    case "num":
	                      $html_string .= $art_child->nodeValue;
	                      break;
	                    
	                    case "rubrica":
	                      $html_string .= $art_child->nodeValue."<br />";
	                      break;
	                    
	                    case "comma":
	                      $comma_id = $art_child->getAttribute("id");
	                      
	                      $html_string .= "<div id = '".$comma_id."'><a name = '".$comma_id."'></a>";
	                      
	                      foreach($art_child->childNodes as $comma_child)
	                      {
	                        switch($comma_child->nodeName)
	                        {
	                          case "num":
	                            $html_string .= $comma_child->nodeValue." ";
	                            break;
	                          
	                          case "corpo":
	                            $html_string .= $comma_child->nodeValue."<br />";
	                            break;
	                          
	                          case "alinea":
	                            $html_string .= $comma_child->nodeValue."<br />";
	                            break;
	                          
	                          case "el":
	                            foreach($comma_child->childNodes as $el_child)
	                            {
	                              switch($el_child->nodeName)
	                              {
	                                case "num":
	                                  $html_string .= $el_child->nodeValue." ";
	                                  break;
	                                
	                                case "corpo":
	                                  $html_string .= $el_child->nodeValue."<br />";
	                                  break;
	                                
	                                default:
	                                  break;
	                              }
	                            }
	                            break;
	                          
	                          default:
	                            break;
	                        }
	                      }
	                      
	                      $html_string .= "</div>";
	                      break;
	                    
	                    default:
	                      break;
	                  }
	                }
	                
	                $html_string .= "</div>";
	                break;
	              
	              default:
	                break;
	            }
	          }
	          
	          $html_string .= "</div>";
	        }
	        break;
	      
	      case "formulafinale":
	        $html_string .= "<div id = 'formulafinale'><a name = 'formulafinale'></a>";
	        
	        foreach($child->childNodes as $nephew)
	        {
	          if(($nephew->prefix == "h")&&($nephew->localName == "p"))
	          {
	            $html_string .= "<br />".$nephew->nodeValue;
	          }
	        }
	        
	        $html_string .= "</div><br />";
	        break;
	
	      case "conclusione":
	        $html_string .= "<div id = 'conclusione'><a name = 'conclusione'></a>";
	      
	        foreach($child->childNodes as $nephew)
	        {
	          switch($nephew->nodeName)
	          {
	            case "firma":
	              $html_string .= $nephew->nodeValue."<br />";
	              break;
	              
	            default:
	              break;
	          }
	        }
	        
	        $html_string .= "</div><br />";
	        break;
	
	    }
	  }
	  
	  return ($html_string);
	}
	
}

?>