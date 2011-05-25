<?php

class XmlParserComponent extends Object {
	
	function parse_string()
	{
	  $file = "laws/brunetta.xml";
	  
	  $xml = new DomDocument();
	  $xml->load($file);
	  
	  // Preleva il tag "DecretoLegislativo" (eventualmente si dovranno gestire gli altri tipi)
	  // NOTA: getElementsByTagName() ritorna una specie di lista, quindi si deve selezionare
	  // solo il primo (nonch unico) elemento.
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
	            // "30 giugno 2003 , n. 196" (cio con uno spazio di troppo prima della virgola).
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
	            // Qui sfrutto due proprietˆ differenti per selezionare solo i
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
	        $titles = $child->getElementsByTagName("titolo");
	        for($i = 0; $i < $titles->length; $i++)
	        {
	          foreach($titles->item($i)->childNodes as $subTitle)
	          {
	            switch ($subTitle->nodeName)
	            {
	              case "num":
	                $art_string .= $subTitle->nodeValue."<br />";
	                break;
	              
	              case "rubrica":
	                $art_string .= $subTitle->nodeValue."<br />";
	                break;
	                
	              case "articolo":
	                foreach($subTitle->childNodes as $art_child)
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
	              
	              case "capo":
	                foreach($subTitle->childNodes as $capo_child)
	                {
	                  switch($capo_child->nodeName)
	                  {
	                    case "num":
	                      $art_string .= $capo_child->nodeValue."<br />";
	                      break;
	
	                    case "rubrica":
	                      $art_string .= $capo_child->nodeValue."<br />";
	                      break;
	                    
	                    case "articolo":
	                      foreach($capo_child->childNodes as $art_child)
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
	              
	              default:
	                break;
	            }
	          }
	        }
	        break;
	      
	      case "formulafinale":
	        break;
	
	      case "conclusione":
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
	  $file = "laws/brunetta.xml";
	  
	  $xml = new DomDocument();
	  $xml->load($file);
	  
	  // Preleva il tag "DecretoLegislativo" (eventualmente si dovranno gestire gli altri tipi)
	  // NOTA: getElementsByTagName() ritorna una specie di lista, quindi si deve selezionare
	  // solo il primo (nonch unico) elemento.
	  $law = $xml->documentElement->getElementsByTagName("DecretoLegislativo")->item(0);
	  
	  // Array per i contatori da ritornare
	  $counters = array();
	  
	  // Inizializza i contatori.
	  $capo_count = 0;
	  $art_count = 0;
	  $comma_count = 0;
	  
	  foreach($law->childNodes as $child)
	  {
	    switch($child->nodeName)
	    {
	      case "articolato":
	        $titles = $child->getElementsByTagName("titolo");
	        for($i = 0; $i < $titles->length; $i++)
	        {
	          $part_count++;
	          foreach($titles->item($i)->childNodes as $subTitle)
	          {
	            switch ($subTitle->nodeName)
	            {
	              case "articolo":
	                $art_count++;
	                foreach($subTitle->childNodes as $art_child)
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
	                
	              case "capo":
	                $capo_count++;
	                foreach($subTitle->childNodes as $capo_child)
	                {
	                  switch($capo_child->nodeName)
	                  {
	                    case "articolo":
	                      $art_count++;
	                      foreach($capo_child->childNodes as $art_child)
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
	  $counters["articoli"] = $art_count;
	  $counters["commi"] = $comma_count;
	  
	  return ($counters);
	}
	
	function parse_json()
	{
	  $file = "laws/brunetta.xml";
	  
	  $xml = new DomDocument();
	  $xml->load($file);
	  
	  // Preleva il tag "DecretoLegislativo" (eventualmente si dovranno gestire gli altri tipi)
	  // NOTA: getElementsByTagName() ritorna una specie di lista, quindi si deve selezionare
	  // solo il primo (nonch unico) elemento.
	  $law = $xml->documentElement->getElementsByTagName("DecretoLegislativo")->item(0);
	  
	  // Contenitore per il json da ritornare
	  $json;
	  
	  // Inizializza i contatori (solo per parti, e articoli - titoli, capi e commi hanno contatori locali).
	  $main_title_count = 0;
	  $main_capo_count = 0;
	  $main_art_count = 0;
	  
	  // Variabili per il json
	  $json_array = array(); // Corrispondente a "DecretoLegislativo"
	  
	  $json_array["identifier"] = "id";
	  $json_array["label"] = "name";
	  $json_array["items"] = array();
	  
	  // Variabili che contengono l'ultimo indice utilizzato negli array per il json di ciascuna categoria
	  $json_title_index;
	  $json_capo_index;
	  $json_art_index;
	  
	  foreach($law->childNodes as $child)
	  {
	    switch($child->nodeName)
	    {
	      case "articolato":
	        $titles = $child->getElementsByTagName("titolo");
	        for($i = 0; $i < $titles->length; $i++)
	        {
	          $main_title_count++;
	          
	          $title_id = $titles->item($i)->getAttribute("id");
	        
	          $json_title_array = array();
	          $json_title_array["id"] = $title_id;
	          $json_title_array["type"] = "titolo";
	          $json_title_array["name"] = "Titolo ".$main_title_count;
	          $json_title_array["children"] = array();
	          
	          $json_array["items"][] = $json_title_array;
	          
	          $json_title_index = count($json_array["items"]) - 1;
	          
	          foreach($titles->item($i)->childNodes as $subTitle)
	          {
	            switch ($subTitle->nodeName)
	            {
	              case "articolo":
	                $main_art_count++;
	                $local_comma_count = 0; // Inizializza contatore locale per i commi del singolo articolo
	              
	                $art_id = $subTitle->getAttribute("id");
	              
	                $json_art_array = array();
	                $json_art_array["id"] = $art_id;
	                $json_art_array["type"] = "articolo";
	                $json_art_array["name"] = "Articolo ".$main_art_count;
	                $json_art_array["children"] = array();
	                
	                $json_array["items"][$json_title_index]["children"][] = array("_reference" => $art_id);
	                $json_array["items"][] = $json_art_array;
	                
	                $json_art_index = count($json_array["items"]) - 1;
	                
	                foreach($subTitle->childNodes as $art_child)
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
	                
	              case "capo":
	                $main_capo_count++;
	              
	                $capo_id = $subTitle->getAttribute("id");
	              
	                $json_capo_array = array();
	                $json_capo_array["id"] = $capo_id;
	                $json_capo_array["type"] = "capo";
	                $json_capo_array["name"] = "Capo ".$main_capo_count;
	                $json_capo_array["children"] = array();
	                
	                $json_array["items"][$json_title_index]["children"][] = array("_reference" => $capo_id);
	                $json_array["items"][] = $json_capo_array;
	                
	                $json_capo_index = count($json_array["items"]) - 1;
	                
	                foreach($subTitle->childNodes as $capo_child)
	                {
	                  switch($capo_child->nodeName)
	                  {
	                    case "articolo":
	                      $main_art_count++;
	                      $local_comma_count = 0; // Inizializza contatore locale per i commi del singolo articolo
	                    
	                      $art_id = $capo_child->getAttribute("id");
	                    
	                      $json_art_array = array();
	                      $json_art_array["id"] = $art_id;
	                      $json_art_array["type"] = "articolo";
	                      $json_art_array["name"] = "Articolo ".$main_art_count;
	                      $json_art_array["children"] = array();
	                      
	                      $json_array["items"][$json_capo_index]["children"][] = array("_reference" => $art_id);
	                      $json_array["items"][] = $json_art_array;
	                      
	                      $json_art_index = count($json_array["items"]) - 1;
	                      
	                      foreach($capo_child->childNodes as $art_child)
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
	  $file = "laws/brunetta.xml";
	  
	  $xml = new DomDocument();
	  $xml->load($file);
	  
	  // Preleva il tag "DecretoLegislativo" (eventualmente si dovranno gestire gli altri tipi)
	  // NOTA: getElementsByTagName() ritorna una specie di lista, quindi si deve selezionare
	  // solo il primo (nonch unico) elemento.
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
	            // "30 giugno 2003 , n. 196" (cio con uno spazio di troppo prima della virgola).
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
	            // Qui sfrutto due proprietˆ differenti per selezionare solo i
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
	      
	        $titles = $child->getElementsByTagName("titolo");
	        for($i = 0; $i < $titles->length; $i++)
	        {
	          $title_id = $titles->item($i)->getAttribute("id");
	          
	          $html_string .= "<div id = '".$title_id."'><a name = '".$title_id."'></a>";
	        
	          foreach($titles->item($i)->childNodes as $subTitle)
	          {
	            switch ($subTitle->nodeName)
	            {
	              case "num":
	                $html_string .= $subTitle->nodeValue."<br />";
	                break;
	              
	              case "rubrica":
	                $html_string .= $subTitle->nodeValue."<br />";
	                break;
	              
	              case "articolo":
	                $art_id = $subTitle->getAttribute("id");
	                      
	                $html_string .= "<div id = '".$art_id."'><a name = '".$art_id."'></a>";
	              
	                foreach($subTitle->childNodes as $art_child)
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
	              
	              case "capo":
	                $capo_id = $subTitle->getAttribute("id");
	                      
	                $html_string .= "<div id = '".$capo_id."'><a name = '".$capo_id."'></a>";
	              
	                foreach($subTitle->childNodes as $capo_child)
	                {
	                  switch($capo_child->nodeName)
	                  {
	                    case "num":
	                      $html_string .= $capo_child->nodeValue."<br />";
	                      break;
	
	                    case "rubrica":
	                      $html_string .= $capo_child->nodeValue."<br />";
	                      break;
	                    
	                    case "articolo":
	                      $art_id = $capo_child->getAttribute("id");
	                      
	                      $html_string .= "<div id = '".$art_id."'><a name = '".$art_id."'></a>";
	                    
	                      foreach($capo_child->childNodes as $art_child)
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
	              
	              default:
	                break;
	            }
	          }
	          
	          $html_string .= "</div>";
	        }
	        break;
	      
	      case "formulafinale":
	        break;
	
	      case "conclusione":
	        break;
	
	    }
	  }
	  
	  return ($html_string);
	}
	
}

?>