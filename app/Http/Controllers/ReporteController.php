<?php
namespace App\Http\Controllers;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use App\Models\AbmSolicitud;
use App\Models\AbmUsuario;
use App\Models\AbmAplicacion;

class ReporteController extends Controller
{          
    protected $fpdf; 
    public function __construct()
    {
        $this->fpdf = new Fpdf;
    }
    public function convertirFechaAmericanaAEspanol($fecha){
        $vFecha = explode("-",$fecha); 
        return $vFecha[2].'/'.$vFecha[1].'/'.$vFecha[0];
    }
    public function index( $id = null) 
    {
      $nombreReporte ='formulario_abm.pdf';    
        if($id) {
            $Usuario = AbmUsuario::with('solicitud','area','sucursal','aplicacionesUsuario.aplicacion','fechasEjecucion')
                                 ->findOrFail($id);            
          //return $Usuario;                      
        }               
        $this->fpdf->AddPage("P");
        $this->fpdf->AliasNbPages();
        //Ubicamos el puntero
        $this->fpdf->SetXY(5, 2);
        //Inicio del encabezado
        // Logos
        $this->fpdf->Image(public_path('images').DIRECTORY_SEPARATOR.'LogoEpsas.png',8,6,23);        
        //Colocando los titulos
        $this->fpdf->SetFont('Arial','B', 8);
        $this->fpdf->Cell(204, 13,  utf8_decode('FORMULARIO DE ABM DE USUARIOS'), 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->fpdf->Ln(3.5);
        $this->fpdf->SetXY(150,8);
       // $this->fpdf->Cell(235,25,utf8_decode('FORMULARIO ABM DE USUARIOS'),0,true, 'C', 0, '', 0, false, 'M', 'M');  
        //Fecha del abm
        $this->fpdf->SetFont('Arial','', 8);
        $this->fpdf->Text($this->fpdf->GetX()-20,$this->fpdf->GetY()+4,"Fecha:");
        //colocando los recuadros para generar las fechas dia/mes/año

        $this->fpdf->Cell(12,7,"",1,false,'C');
        $this->fpdf->Cell(12,7,"",1,false,'C');
        $this->fpdf->Cell(12,7,"",1,false,'C');
        //Colocando los textos de dia mes y año 
        $this->fpdf->Text($this->fpdf->GetX()-33,$this->fpdf->GetY()+10,"Dia");
        $this->fpdf->Text($this->fpdf->GetX()-22,$this->fpdf->GetY()+10,"Mes");
        $this->fpdf->Text($this->fpdf->GetX()-10,$this->fpdf->GetY()+10, utf8_decode("Año"));
         //llenando datos de dia/mes/año
        if($id) {
          if($Usuario->d_fecha){
            $vFecha = explode("-",$Usuario->d_fecha);              
            $this->fpdf->Text($this->fpdf->GetX()-31,$this->fpdf->GetY()+5,$vFecha[2]);
            $this->fpdf->Text($this->fpdf->GetX()-20,$this->fpdf->GetY()+5,$vFecha[1]);
            $this->fpdf->Text($this->fpdf->GetX()-10,$this->fpdf->GetY()+5,$vFecha[0]);
          }             
       }         
        //Datos personales del usuario
        $this->fpdf->Ln();        
        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->Text($this->fpdf->GetX()-8,$this->fpdf->GetY() + 8,"Datos Personales del Usuario:"); 
        $this->fpdf->SetXY(1,20);      
        $this->fpdf->Cell(207,60,"",1,false,'C');
        //Llenando datos de Personales de empleado}        
        $this->fpdf->Line($this->fpdf->GetX()-203,$this->fpdf->GetY() + 9,$this->fpdf->GetX()-5,$this->fpdf->GetY() + 9);
        //Llenando los datos  

        //Colocando los labels de apellido paterno, materno y  nombres
        $this->fpdf->SetFont('Arial','',8);
        $this->fpdf->Text($this->fpdf->GetX()-180,$this->fpdf->GetY() + 12,"Apellido Paterno"); 
        $this->fpdf->Text($this->fpdf->GetX()-120,$this->fpdf->GetY() + 12,"Apellido Materno");
        $this->fpdf->Text($this->fpdf->GetX()-60,$this->fpdf->GetY() + 12,"Nombres(s)");
        //Llenado nombre y apellidos del usuario si existen

        if($id) {
        $this->fpdf->Text($this->fpdf->GetX()-175,$this->fpdf->GetY() + 8,$Usuario->v_paterno); 
        $this->fpdf->Text($this->fpdf->GetX()-115,$this->fpdf->GetY() + 8,$Usuario->v_materno);
        $this->fpdf->Text($this->fpdf->GetX()-65,$this->fpdf->GetY() + 8,$Usuario->v_nombres);      
        } 


        //colocando las lineas para ci, item y puesto o cargo
        //ci
        $this->fpdf->Line($this->fpdf->GetX()-203,$this->fpdf->GetY() + 17,$this->fpdf->GetX()-160,$this->fpdf->GetY() + 17);
        //item
        $this->fpdf->Line($this->fpdf->GetX()-155,$this->fpdf->GetY() + 17,$this->fpdf->GetX()-135,$this->fpdf->GetY() + 17);
        //puesto o cargo
        $this->fpdf->Line($this->fpdf->GetX()-130,$this->fpdf->GetY() + 17,$this->fpdf->GetX()-5,$this->fpdf->GetY() + 17);
        //colocando los labels
        //ci
        $this->fpdf->Text($this->fpdf->GetX()-200,$this->fpdf->GetY() + 20,"Nro.Carnet de Identidad");
        //nro item
        $this->fpdf->Text($this->fpdf->GetX()-152,$this->fpdf->GetY() + 20,"Nro.Item");
        //puesto o cargo
        $this->fpdf->Text($this->fpdf->GetX()-80,$this->fpdf->GetY() + 20,"Puesto o cargo");
        //Llenando los datos si existen 

        if($id){
        //ci
        $this->fpdf->Text($this->fpdf->GetX()-190,$this->fpdf->GetY() + 16,$Usuario->n_ci);
        //item
        $this->fpdf->Text($this->fpdf->GetX()-150,$this->fpdf->GetY() + 16,$Usuario->n_item);
        //cargo
        $this->fpdf->Text($this->fpdf->GetX()-95,$this->fpdf->GetY() + 16,$Usuario->v_cargo);
        }                
        //Area
        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->Text($this->fpdf->GetX()-205,$this->fpdf->GetY() + 25,"Area:");
        $this->fpdf->SetFont('Arial','',7);
        $this->fpdf->Text($this->fpdf->GetX()-205,$this->fpdf->GetY() + 30,utf8_decode("Técnica"));
        $this->fpdf->Text($this->fpdf->GetX()-205,$this->fpdf->GetY() + 36,"Comercial");
        $this->fpdf->Text($this->fpdf->GetX()-205,$this->fpdf->GetY() + 42,"Administrativa");
        //dibujando los cuadritos
        if($id){              
            if($Usuario->area->n_id == 1) 
            {                
            $this->fpdf->SetXY(20,45);  
            $this->fpdf->Cell(10,6,"X",1,false,'C');             
            $this->fpdf->SetXY(20,51);
            $this->fpdf->Cell(10,6,"",1,false,'C'); 
            $this->fpdf->SetXY(20,57);
            $this->fpdf->Cell(10,6,"",1,false,'C');             
            }             
            if($Usuario->area->n_id == 2) {
            $this->fpdf->SetXY(20,45);  
            $this->fpdf->Cell(10,6,"",1,false,'C');             
            $this->fpdf->SetXY(20,51);
            $this->fpdf->Cell(10,6,"X",1,false,'C'); 
            $this->fpdf->SetXY(20,57);
            $this->fpdf->Cell(10,6,"",1,false,'C');                
            }                        
            if($Usuario->area->n_id == 3) {            
            $this->fpdf->SetXY(20,45);  
            $this->fpdf->Cell(10,6,"",1,false,'C');             
            $this->fpdf->SetXY(20,51);
            $this->fpdf->Cell(10,6,"",1,false,'C'); 
            $this->fpdf->SetXY(20,57);
            $this->fpdf->Cell(10,6,"X",1,false,'C'); 
            }            
        } else {
            $this->fpdf->SetXY(20,45);          
            $this->fpdf->Cell(10,6,"",1,false,'C');         
            $this->fpdf->SetXY(20,51);
            $this->fpdf->Cell(10,6,"",1,false,'C'); 
            $this->fpdf->SetXY(20,57);
            $this->fpdf->Cell(10,6,"",1,false,'C'); 
        }
        

        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->Text($this->fpdf->GetX()-28,$this->fpdf->GetY() + 11,"PERSONAL EVENTUAL");
        $this->fpdf->SetFont('Arial','',7);


        $this->fpdf->Text($this->fpdf->GetX()-28,$this->fpdf->GetY() + 15,"CONTRATO DE:");
        $this->fpdf->Text($this->fpdf->GetX()-28,$this->fpdf->GetY() + 19,"HASTA:");

        //Dibujando las lineas si es eventual o no
        $this->fpdf->Line($this->fpdf->GetX()-5,$this->fpdf->GetY() + 15,$this->fpdf->GetX()+30,$this->fpdf->GetY() + 15);

        $this->fpdf->Line($this->fpdf->GetX()-5,$this->fpdf->GetY() + 19,$this->fpdf->GetX()+30,$this->fpdf->GetY() + 19);

        if($id) {                 
          if($Usuario->n_eventual == 1) {            
            if($Usuario->d_inicio_contrato) {
                $fechaI = $this->convertirFechaAmericanaAEspanol($Usuario->d_inicio_contrato);
                $this->fpdf->Text($this->fpdf->GetX()+5,$this->fpdf->GetY() + 14,$fechaI);             
            }  
            if($Usuario->d_fin_contrato) {
                $fechaF = $this->convertirFechaAmericanaAEspanol($Usuario->d_fin_contrato);
                $this->fpdf->Text($this->fpdf->GetX()+5,$this->fpdf->GetY() + 18, $fechaF);          
            }            
          }         
        }
        //Ahora departamento, division, oficina/sucursal, telefonos y interno

        $this->fpdf->SetXY(60,60); 
        //linea de departamento         
        $this->fpdf->Line($this->fpdf->GetX()-25,$this->fpdf->GetY() - 12,$this->fpdf->GetX()+143,$this->fpdf->GetY() - 12);
        //Label de departamento y division
        if($id){
          $this->fpdf->Text($this->fpdf->GetX()+7,$this->fpdf->GetY() - 14,$Usuario->v_departamento);
        }        
        $this->fpdf->Text($this->fpdf->GetX()+15,$this->fpdf->GetY() - 9,"Departamento");        
        if($id){
          $this->fpdf->Text($this->fpdf->GetX()+80,$this->fpdf->GetY() - 14,$Usuario->v_division);
        }
        $this->fpdf->Text($this->fpdf->GetX()+85,$this->fpdf->GetY() - 9,utf8_decode("División"));      
        //Linea de Oficina Sucursal telefonos
       
        $this->fpdf->Line($this->fpdf->GetX()-25,$this->fpdf->GetY() - 2,$this->fpdf->GetX()+143,$this->fpdf->GetY() - 2);
        //Label oficina/sucursal 
        if($id){
          $this->fpdf->Text($this->fpdf->GetX()-7,$this->fpdf->GetY()-3,utf8_decode($Usuario->sucursal->v_descripcion));   
        }
        $this->fpdf->Text($this->fpdf->GetX()-5,$this->fpdf->GetY() +1,utf8_decode("Oficina/Sucursal"));
        //Label Telefonos
        if($id){
        $this->fpdf->Text($this->fpdf->GetX()+40,$this->fpdf->GetY()-3,utf8_decode($Usuario->v_telefonos));   
        }        
        $this->fpdf->Text($this->fpdf->GetX()+45,$this->fpdf->GetY() +1,utf8_decode("Telefonos"));
        //Label Interno
        if($id){
        $this->fpdf->Text($this->fpdf->GetX()+100,$this->fpdf->GetY()-3,utf8_decode($Usuario->n_interno));   
        } 
        $this->fpdf->Text($this->fpdf->GetX()+100,$this->fpdf->GetY() +1,utf8_decode("Interno"));  
       //Dibujando el recuadro de tipo de solicitud
        $this->fpdf->SetXY(1,80);
        $this->fpdf->Cell(92,20,"",1,false,'C');        
       //Label de tipo de solicitud 
        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->Text($this->fpdf->GetX()-90,83,"Tipo de solicitud");  


       if($id) {
        if($Usuario->n_id_solicitud == 1){
        $this->fpdf->SetXY(80,81);  
        $this->fpdf->SetFont('Arial','',7);
        $this->fpdf->Text($this->fpdf->GetX()-25,84,"Alta");          
        $this->fpdf->Cell(10,6,"X",1,false,'C');  
                
        $this->fpdf->SetXY(80,87);
        $this->fpdf->Text($this->fpdf->GetX()-25,91,"Baja");
        $this->fpdf->Cell(10,6,"",1,false,'C'); 

        $this->fpdf->SetXY(80,93);
        $this->fpdf->Text($this->fpdf->GetX()-25,98,utf8_decode("Modificación"));
        $this->fpdf->Cell(10,6,"",1,false,'C'); 
        }        
        if($Usuario->n_id_solicitud == 2)
        {
        $this->fpdf->SetXY(80,81);  
        $this->fpdf->SetFont('Arial','',7);
        $this->fpdf->Text($this->fpdf->GetX()-25,84,"Alta");          
        $this->fpdf->Cell(10,6,"",1,false,'C');  
                
        $this->fpdf->SetXY(80,87);
        $this->fpdf->Text($this->fpdf->GetX()-25,91,"Baja");
        $this->fpdf->Cell(10,6,"X",1,false,'C'); 

        $this->fpdf->SetXY(80,93);
        $this->fpdf->Text($this->fpdf->GetX()-25,98,utf8_decode("Modificación"));
        $this->fpdf->Cell(10,6,"",1,false,'C'); 
        }        
        if($Usuario->n_id_solicitud == 3){
        $this->fpdf->SetXY(80,81);  
        $this->fpdf->SetFont('Arial','',7);
        $this->fpdf->Text($this->fpdf->GetX()-25,84,"Alta");          
        $this->fpdf->Cell(10,6,"",1,false,'C');  
                
        $this->fpdf->SetXY(80,87);
        $this->fpdf->Text($this->fpdf->GetX()-25,91,"Baja");
        $this->fpdf->Cell(10,6,"",1,false,'C'); 

        $this->fpdf->SetXY(80,93);
        $this->fpdf->Text($this->fpdf->GetX()-25,98,utf8_decode("Modificación"));
        $this->fpdf->Cell(10,6,"X",1,false,'C'); 
        }    
       } else {
        $this->fpdf->SetXY(80,81);  
        $this->fpdf->SetFont('Arial','',7);
        $this->fpdf->Text($this->fpdf->GetX()-25,84,"Alta");          
        $this->fpdf->Cell(10,6,"",1,false,'C');  
                
        $this->fpdf->SetXY(80,87);
        $this->fpdf->Text($this->fpdf->GetX()-25,91,"Baja");
        $this->fpdf->Cell(10,6,"",1,false,'C'); 

        $this->fpdf->SetXY(80,93);
        $this->fpdf->Text($this->fpdf->GetX()-25,98,utf8_decode("Modificación"));
        $this->fpdf->Cell(10,6,"",1,false,'C'); 
       }         
       //Dibujando el recuadro de la aplicacion
        $this->fpdf->SetXY(93,80);
        $this->fpdf->Cell(115,20,"",1,false,'C');
        //Colocando el label de aplicacion
        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->Text($this->fpdf->GetX()-113,83,utf8_decode("Aplicación"));
        //return $Usuario->aplicacionesUsuario.aplicacion;     
        if($id){          
           $Aplicacion = json_decode($Usuario);
           $Aplicacion = $Aplicacion->aplicaciones_usuario;           
        } else {
          $Aplicacion = AbmAplicacion::orderBy('n_id','asc')                                
                                     ->get();
        }       
           
       //  return $Aplicacion;
        //Dibujando los cuadros de aplicacion  
            $i = 0;
            $this->fpdf->SetFont('Arial','',8);                                                       
            foreach ($Aplicacion as $valor) {
                //return $Aplicacion->aplicaciones_usuario;             
             $i++;
             if($i == 1 or $i == 2 or $i == 3) {
                 if($i == 1)
                 {                      
                   if($valor->n_id == 1 and !$id)
                   {
                    $this->fpdf->SetXY(122,81);
                    $this->fpdf->Text(110,85,$valor->v_descripcion);  
                    $this->fpdf->Cell(10,6,"",1,false,'C');    
                   }                    
                   elseif ($valor->aplicacion->n_id == 1 and $id) {
                    $this->fpdf->SetXY(122,81);
                    $this->fpdf->Text(110,85,$valor->aplicacion->v_descripcion);  
                    $this->fpdf->Cell(10,6,"X",1,false,'C');    
                   } 

                 }
                 if($i == 2)
                 {
                   if($valor->n_id == 2 and !$id)
                   {
                     $this->fpdf->SetXY(122,87);
                     $this->fpdf->Text(110,91,$valor->v_descripcion);   
                     $this->fpdf->Cell(10,6,"",1,false,'C');     
                   } elseif ($valor->aplicacion->n_id == 2 and $id){
                     $this->fpdf->SetXY(122,87);
                     $this->fpdf->Text(110,91,$valor->aplicacion->v_descripcion);   
                     $this->fpdf->Cell(10,6,"X",1,false,'C');       
                   }                   
                 }
                 if($i == 3)
                 {
                   if($valor->n_id == 3 and !$id){
                     $this->fpdf->SetXY(122,93);  
                     $this->fpdf->Text(110,97,$valor->v_descripcion);
                     $this->fpdf->Cell(10,6,"",1,false,'C');    
                   } elseif ($valor->aplicacion->n_id == 3 and $id) {
                     $this->fpdf->SetXY(122,93);  
                     $this->fpdf->Text(110,97,$valor->aplicacion->v_descripcion);
                     $this->fpdf->Cell(10,6,"X",1,false,'C');    
                   }
                   
                 }                    
             }
             if($i == 4 or $i == 5 or $i == 6){
               if($i == 4)
               {
                 if($valor->n_id == 4 and !$id){
                   $this->fpdf->SetXY(153,81);  
                   $this->fpdf->Text(133,85,$valor->v_descripcion);
                   $this->fpdf->Cell(10,6,"",1,false,'C');    
                 } elseif($valor->aplicacion->n_id == 4 and $id) {
                   $this->fpdf->SetXY(153,81);  
                   $this->fpdf->Text(133,85,$valor->aplicacion->v_descripcion);
                   $this->fpdf->Cell(10,6,"X",1,false,'C');    
                 }                    
               }
               if($i == 5)
               {
                 if($valor->n_id == 5 and !$id) {
                   $this->fpdf->SetXY(153,87);  
                   $this->fpdf->Text(133,91,$valor->v_descripcion);
                   $this->fpdf->Cell(10,6,"",1,false,'C');      
                 } elseif($valor->aplicacion->n_id == 5 and $id) {
                   $this->fpdf->SetXY(153,87);  
                   $this->fpdf->Text(133,91,$valor->aplicacion->v_descripcion);
                   $this->fpdf->Cell(10,6,"X",1,false,'C');    
                 }                    
               }               
               if($i == 6)
               {
                 if($valor->n_id == 6 and !$id){
                 $this->fpdf->SetXY(153,93);  
                 $this->fpdf->Text(133,97,$valor->v_descripcion);
                 $this->fpdf->Cell(10,6,"",1,false,'C');    
                 } elseif($valor->aplicacion->n_id == 6 and $id)  {
                 $this->fpdf->SetXY(153,93);  
                 $this->fpdf->Text(133,97,$valor->aplicacion->v_descripcion);
                 $this->fpdf->Cell(10,6,"X",1,false,'C');      
                 } 
               }
             }               
             if($i == 7 or $i == 8 or $i == 9 or $i == 10) {
                  if($i == 7)
                  {
                  if($valor->n_id == 7 and !$id){
                    $this->fpdf->SetXY(178,81);  
                    $this->fpdf->Text(164,85,$valor->v_descripcion);
                    $this->fpdf->Cell(10,6,"",1,false,'C');    
                  } elseif($valor->aplicacion->n_id == 7 and $id) {
                    $this->fpdf->SetXY(178,81);  
                    $this->fpdf->Text(164,85,$valor->aplicacion->v_descripcion);
                    $this->fpdf->Cell(10,6,"X",1,false,'C');    
                  }                   
                 }
                 if($i == 8)
                 {
                  if($valor->n_id == 8 and !$id){
                    $this->fpdf->SetXY(178,87);  
                    $this->fpdf->Text(164,91,$valor->v_descripcion);
                    $this->fpdf->Cell(10,6,"",1,false,'C');    
                  } elseif($valor->aplicacion->n_id == 8 and $id){
                    $this->fpdf->SetXY(178,87);  
                    $this->fpdf->Text(164,91,$valor->aplicacion->v_descripcion);
                    $this->fpdf->Cell(10,6,"X",1,false,'C');    
                  }
                   
                 }
                 if($i == 9)
                 {
                  if($valor->n_id == 9 and !$id){
                    $this->fpdf->SetXY(178,93);  
                    $this->fpdf->Text(164,97,$valor->v_descripcion);
                    $this->fpdf->Cell(10,6,"",1,false,'C');    
                  }elseif($valor->aplicacion->n_id == 9 and $id){
                    $this->fpdf->SetXY(178,93);  
                    $this->fpdf->Text(164,97,$valor->aplicacion->v_descripcion);
                    $this->fpdf->Cell(10,6,"X",1,false,'C');    
                  }                   
                 }                                     
                 if($i == 10)
                 {
                  if($valor->n_id == 10 and !$id){
                   $this->fpdf->SetXY(196,81); 
                   $this->fpdf->Text(190,85,$valor->v_descripcion); 
                   $this->fpdf->Cell(10,6,"",1,false,'C');
                   //Dibujando la linea de otro                      
                   $this->fpdf->Line(190,93,207,93); 
                  }elseif($valor->aplicacion->n_id == 10 and $id){
                   $this->fpdf->SetXY(196,81); 
                   $this->fpdf->Text(190,85,$valor->aplicacion->v_descripcion); 
                   $this->fpdf->Cell(10,6,"X",1,false,'C');
                   //Dibujando la linea de otro  
                   $this->fpdf->Text(190,91,$Usuario->v_otro);                     
                   $this->fpdf->Line(190,93,207,93); 
                  }
                 }
             }                                
            }  


            

            
        //Dibujando el cuadro de Acceso/Permiso
        //Ubicando el puntero en otro lugar
        $this->fpdf->SetXY(1,100);                           
        $this->fpdf->Cell(207,27,"",1,false,'C');
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Text(5,103,"Acceso/Permiso"); 
        //Linea de Perfil/Papel/Rol
        $this->fpdf->Line(5,108,120,108);
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->Text(120,108,"* Equivalente a un Usuario existente en su Area"); 
        if($id){
          $this->fpdf->Text(50,107,$Usuario->v_perfil_papel_rol);   
        }   
        $this->fpdf->Text(55,111,"Perfil/Papel/Rol"); 
        //Linea para Modulo/Programa/Carpeta
        $this->fpdf->Line(5,115,120,115);
        $this->fpdf->SetFont('Arial', '', 8);
        if($id){
          $this->fpdf->Text(55,114,$Usuario->v_modulo_programa_carpeta);   
        }  
        $this->fpdf->Text(120,115,"* Nombre del Modulo, programa o carpeta de acceso"); 
        $this->fpdf->Text(48,118,"Modulo/Programa/Carpeta"); 
        //Linea para Funcionalidad y Permisos
        $this->fpdf->Line(5,122,120,122);
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->Text(120,122,"* Actividad o tarea a Realizar"); 

        if($id){
          $this->fpdf->Text(37,121,$Usuario->v_funcionalidad_permisos);   
        }  
        $this->fpdf->Text(50,125,"Funcionalidad o Permisos"); 
        //Dibujando el cuadro de Observacion              
     
        if($id){
          $this->fpdf->Text(5,135,$Usuario->v_observacion);   
        }  
        $this->fpdf->SetXY(1,127); 
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Text(5,130,utf8_decode("Observación/Justificación de la solicitud"));        
        $this->fpdf->Cell(207,15,"",1,false,'C');        
        $this->fpdf->SetFont('Arial','B', 8);
        $this->fpdf->SetXY(1,127); 
        $this->fpdf->Cell(207,15,"",1,false,'C');         
        //Cuadro para la firma del usuario solicitante
        $this->fpdf->SetXY(1,142); 
        $this->fpdf->Cell(107,25,"",1,false,'C'); 
        $this->fpdf->Cell(100,25,"",1,false,'C'); 
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Text(38,145,utf8_decode("Firma del Usuario Solicitante"));        
        //linea para la firma del usuario solicitante
        $this->fpdf->Line(8,162,100,162); 
        //Texto donde firmara el usuario solicitante
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->Text(33,165,utf8_decode("Acepto cumplir las Políticas de Sistemas"));
        //No queda de otra que dibujar lineas para la firma del usuario solicitante       
        //Para el visto Bueno de la gerencia
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Text(145,145,utf8_decode("V°B° Gerente de Area"));    
        //Linea de la firma
        $this->fpdf->Line(115,162,200,162);
        //Texto de la firma del gerente
        $this->fpdf->SetFont('Arial', '', 8);
        $this->fpdf->Text(143,165,utf8_decode("Firma de Autorización"));
        //Para el uso exclusivo de sistemas
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Text(5,171,utf8_decode("USO EXCLUSIVO DE SISTEMAS"));
        $this->fpdf->SetXY(1,167); 
        $this->fpdf->Cell(207,30,"",1,false,'C');
        $this->fpdf->SetFont('Arial', '', 8);
        //Fecha y hora de recepcion
        $this->fpdf->Text(5,175,utf8_decode("Fecha-Hora de Recepción"));
        if($id){
          $this->fpdf->Text(40,175,$Usuario->fecha_recepcion);   
        } 
        $this->fpdf->Line(40,176,65,176); 
        //Nro mesa de ayuda
        if($id){
          $this->fpdf->Text(113,175,$Usuario->n_numero_mesa_ayuda);   
        } 

        $this->fpdf->Text(85,175,utf8_decode("Nro.Mesa de Ayuda"));
        $this->fpdf->Line(112,176,125,176);        
        //Recibio
        if($id){
          $this->fpdf->Text(40,182,$Usuario->v_nombre_recibio);   
        } 
        $this->fpdf->Text(5,182,utf8_decode("Recibio"));
        $this->fpdf->Line(40,183,115,183); 
        $this->fpdf->Text(72,186,utf8_decode("Nombre"));
        //Linea de la firma
        $this->fpdf->Line(130,183,165,183); 
        $this->fpdf->Text(144,186,utf8_decode("Firma"));
        //0bservaciones
        if($id){
          $this->fpdf->Text(40,190,$Usuario->v_observacion_recibio);   
        } 
        $this->fpdf->Text(5,190,utf8_decode("Observaciones"));
        $this->fpdf->Line(40,191,175,191); 
        //Creando ejecucion de solicitud
        $this->fpdf->SetXY(1,197); 
        $this->fpdf->Cell(207,75,"",1,false,'C');
        //Ejecucion de la solicitud      
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Text(5,202,utf8_decode("EJECUCION DE LA SOLICITUD"));
        if($id){          
          $Ejecucion = json_decode($Usuario);
          $Ejecucion = $Ejecucion->fechas_ejecucion;           
        }
       // return $Ejecucion;
        //Fecha de ejecucion
        $this->fpdf->SetFont('Arial', '', 8);
        $i = 0;       
        if($id){
          foreach ($Ejecucion as $dato) {
            $i++;
           if($i==1)
           {
            $this->fpdf->Text(5,210,utf8_decode("Fecha Ejecución 1"));
            if($id) {
              $this->fpdf->Text(35,210,$dato->fecha_ejecucion);
            }
            $this->fpdf->Line(31,211,55,211); 
            $this->fpdf->Text(34,214,utf8_decode("Dia/Mes/Año"));
            $this->fpdf->Line(65,211,165,211);
            $this->fpdf->Text(114,214,utf8_decode("Nombre 1")); 
            if($id) {
              $this->fpdf->Text(90,210,$dato->v_nombre);
            }

            $this->fpdf->Line(170,211,195,211);
            $this->fpdf->Text(173,214,utf8_decode("Nro Asignación 1")); 
            if($id) {
              $this->fpdf->Text(180,210,$dato->n_nro_asignacion);
            }

           }        
           if($i==2){
            $this->fpdf->Text(5,222,utf8_decode("Fecha Ejecución 2"));

            if($id) {
              $this->fpdf->Text(35,222,$dato->fecha_ejecucion);
            }
            $this->fpdf->Line(31,223,55,223); 
            $this->fpdf->Text(34,226,utf8_decode("Dia/Mes/Año"));
            $this->fpdf->Line(65,223,165,223);
            $this->fpdf->Text(114,226,utf8_decode("Nombre 2"));
            if($id) {
              $this->fpdf->Text(90,222,$dato->v_nombre);
            }
 
            $this->fpdf->Line(170,223,195,223);
            $this->fpdf->Text(173,226,utf8_decode("Nro Asignación 2")); 
            if($id) {
              $this->fpdf->Text(180,222,$dato->n_nro_asignacion);
            }
           }
           if($i==3){
            $this->fpdf->Text(5,234,utf8_decode("Fecha Ejecución 3"));
            if($id) {
              $this->fpdf->Text(35,234,$dato->fecha_ejecucion);
            }
            $this->fpdf->Line(31,235,55,235);
            $this->fpdf->Text(34,238,utf8_decode("Dia/Mes/Año"));   
            $this->fpdf->Line(65,235,165,235);
            $this->fpdf->Text(114,238,utf8_decode("Nombre 3"));
            if($id) {
              $this->fpdf->Text(90,234,$dato->v_nombre);
            }
 
            $this->fpdf->Line(170,235,195,235);
            $this->fpdf->Text(173,238,utf8_decode("Nro Asignación 3")); 

            if($id) {
              $this->fpdf->Text(180,234,$dato->n_nro_asignacion);
            }
           }           
           if($i==4){
            $this->fpdf->Text(5,246,utf8_decode("Fecha Ejecución 4"));
            if($id) {
              $this->fpdf->Text(35,246,$dato->fecha_ejecucion);
            }

            $this->fpdf->Line(31,247,55,247);
            $this->fpdf->Text(34,250,utf8_decode("Dia/Mes/Año"));   
            $this->fpdf->Line(65,247,165,247);
            $this->fpdf->Text(114,250,utf8_decode("Nombre 4"));
            if($id) {
              $this->fpdf->Text(90,246,$dato->v_nombre);
            }

            
            $this->fpdf->Line(170,247,195,247);
            $this->fpdf->Text(173,250,utf8_decode("Nro Asignación 4")); 
            if($id) {
              $this->fpdf->Text(180,246,$dato->n_nro_asignacion);
            }
            

           }

           if($i==5){
            $this->fpdf->Text(5,258,utf8_decode("Fecha Ejecución 5"));
            if($id) {
              $this->fpdf->Text(35,258,$dato->fecha_ejecucion);
            }
            $this->fpdf->Line(31,259,55,259);
            $this->fpdf->Text(34,262,utf8_decode("Dia/Mes/Año"));   
            $this->fpdf->Line(65,259,165,259);
            $this->fpdf->Text(114,262,utf8_decode("Nombre 5")); 
            if($id) {
              $this->fpdf->Text(90,258,$dato->v_nombre);
            }
            $this->fpdf->Line(170,259,195,259);
            $this->fpdf->Text(173,262,utf8_decode("Nro Asignación 5"));
            if($id) {
              $this->fpdf->Text(180,258,$dato->n_nro_asignacion);
            }
           }    
          }                                
        } else {
        
        $this->fpdf->Text(5,210,utf8_decode("Fecha Ejecución 1"));
        $this->fpdf->Line(31,211,55,211); 
        $this->fpdf->Text(34,214,utf8_decode("Dia/Mes/Año"));
        $this->fpdf->Line(65,211,165,211);
        $this->fpdf->Text(114,214,utf8_decode("Nombre 1")); 
        $this->fpdf->Line(170,211,195,211);
        $this->fpdf->Text(173,214,utf8_decode("Nro Asignación 1")); 


        $this->fpdf->Text(5,222,utf8_decode("Fecha Ejecución 2"));
        $this->fpdf->Line(31,223,55,223); 
        $this->fpdf->Text(34,226,utf8_decode("Dia/Mes/Año"));
        $this->fpdf->Line(65,223,165,223);
        $this->fpdf->Text(114,226,utf8_decode("Nombre 2")); 
        $this->fpdf->Line(170,223,195,223);
        $this->fpdf->Text(173,226,utf8_decode("Nro Asignación 2")); 

       
        $this->fpdf->Text(5,234,utf8_decode("Fecha Ejecución 3"));
        $this->fpdf->Line(31,235,55,235);
        $this->fpdf->Text(34,238,utf8_decode("Dia/Mes/Año"));   
        $this->fpdf->Line(65,235,165,235);
        $this->fpdf->Text(114,238,utf8_decode("Nombre 3")); 
        $this->fpdf->Line(170,235,195,235);
        $this->fpdf->Text(173,238,utf8_decode("Nro Asignación 3")); 


        $this->fpdf->Text(5,246,utf8_decode("Fecha Ejecución 4"));
        $this->fpdf->Line(31,247,55,247);
        $this->fpdf->Text(34,250,utf8_decode("Dia/Mes/Año"));   
        $this->fpdf->Line(65,247,165,247);
        $this->fpdf->Text(114,250,utf8_decode("Nombre 4")); 
        $this->fpdf->Line(170,247,195,247);
        $this->fpdf->Text(173,250,utf8_decode("Nro Asignación 4")); 

        $this->fpdf->Text(5,258,utf8_decode("Fecha Ejecución 5"));
        $this->fpdf->Line(31,259,55,259);
        $this->fpdf->Text(34,262,utf8_decode("Dia/Mes/Año"));   
        $this->fpdf->Line(65,259,165,259);
        $this->fpdf->Text(114,262,utf8_decode("Nombre 5")); 
        $this->fpdf->Line(170,259,195,259);
        $this->fpdf->Text(173,262,utf8_decode("Nro Asignación 5"));


        }


        $this->fpdf->Text(5,210,utf8_decode("Fecha Ejecución 1"));
        $this->fpdf->Line(31,211,55,211); 
        $this->fpdf->Text(34,214,utf8_decode("Dia/Mes/Año"));
        $this->fpdf->Line(65,211,165,211);
        $this->fpdf->Text(114,214,utf8_decode("Nombre 1")); 
        $this->fpdf->Line(170,211,195,211);
        $this->fpdf->Text(173,214,utf8_decode("Nro Asignación 1")); 


        $this->fpdf->Text(5,222,utf8_decode("Fecha Ejecución 2"));
        $this->fpdf->Line(31,223,55,223); 
        $this->fpdf->Text(34,226,utf8_decode("Dia/Mes/Año"));
        $this->fpdf->Line(65,223,165,223);
        $this->fpdf->Text(114,226,utf8_decode("Nombre 2")); 
        $this->fpdf->Line(170,223,195,223);
        $this->fpdf->Text(173,226,utf8_decode("Nro Asignación 2")); 

       
        $this->fpdf->Text(5,234,utf8_decode("Fecha Ejecución 3"));
        $this->fpdf->Line(31,235,55,235);
        $this->fpdf->Text(34,238,utf8_decode("Dia/Mes/Año"));   
        $this->fpdf->Line(65,235,165,235);
        $this->fpdf->Text(114,238,utf8_decode("Nombre 3")); 
        $this->fpdf->Line(170,235,195,235);
        $this->fpdf->Text(173,238,utf8_decode("Nro Asignación 3")); 


        $this->fpdf->Text(5,246,utf8_decode("Fecha Ejecución 4"));
        $this->fpdf->Line(31,247,55,247);
        $this->fpdf->Text(34,250,utf8_decode("Dia/Mes/Año"));   
        $this->fpdf->Line(65,247,165,247);
        $this->fpdf->Text(114,250,utf8_decode("Nombre 4")); 
        $this->fpdf->Line(170,247,195,247);
        $this->fpdf->Text(173,250,utf8_decode("Nro Asignación 4")); 

        $this->fpdf->Text(5,258,utf8_decode("Fecha Ejecución 5"));
        $this->fpdf->Line(31,259,55,259);
        $this->fpdf->Text(34,262,utf8_decode("Dia/Mes/Año"));   
        $this->fpdf->Line(65,259,165,259);
        $this->fpdf->Text(114,262,utf8_decode("Nombre 5")); 
        $this->fpdf->Line(170,259,195,259);
        $this->fpdf->Text(173,262,utf8_decode("Nro Asignación 5"));









 


        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Text(5,278,utf8_decode("Nota: Este formulario debe ir acompañado al documento de Condiciones Generales de Acceso"));









        


        
        
        $this->fpdf->Output(public_path($nombreReporte), 'F');
        return response()->json(['enlace' => url($nombreReporte)], 200);
        

    




       // $this->fpdf->SetFont('Arial', 'B', 15);
       // $this->fpdf->AddPage("L", ['100', '100']);
       // $this->fpdf->Text(10, 10, "Hello World!");                
        //$this->fpdf->Output();
        //exit;
    }

}
