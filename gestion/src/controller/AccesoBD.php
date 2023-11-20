<?php
namespace App\controller;
use Psr\Container\ContainerInterface;
use PDO;

class AccesoBD {

  protected $container;

  public function __construct(ContainerInterface $c){
    $this->container = $c;
  }

  private function generarParam($datos){
    $cad = "(";
    foreach($datos as $campo => $valor){
      $cad .= ":$campo,";
    }
    $cad = trim($cad,',');
    $cad .= ");";
    return $cad;
  }

  public function crearBD($datos,$recurso){
    $params = $this->generarParam($datos);
    $sql = "SELECT nuevo$recurso$params";
    $d = [];
    foreach($datos as $clave => $valor){
      $d[$clave] = $valor;
    }
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute($d);
    $res = $query->fetch(PDO::FETCH_NUM);
    $query = null;
    $con = null;
    return $res[0];
  }

  public function crearUsrBD($datos,$recurso, $rol, $campoId){
    $passw = $datos->passw;
    unset($datos->passw);
    $params = $this->generarParam($datos);
    $con = $this->container->get('bd');
    $con->beginTransaction();
    try {
      $sql = "SELECT nuevo$recurso$params";
      $query = $con->prepare($sql);
      $d = [];
      foreach($datos as $clave => $valor){
        $d[$clave] = filter_var($valor, FILTER_SANITIZE_SPECIAL_CHARS);
      }
      $query->execute($d);
      $res = $query->fetch(PDO::FETCH_NUM)[0];
      //creando usuario
      $sql = "SELECT nuevoUsuario(:usr, :rol, :passw);";
      $query = $con->prepare($sql);
      $query->execute(array(
        'usr' => $d[$campoId],
        'rol' => $rol,
        'passw' => $passw
      ));
      $con->commit();
    } catch (PDOException $ex) {
      print_r($ex->getMessage()); //se quita en produccion
      $con->rollback();
      $res = 2;
    }
    $query = null;
    $con = null;
    return $res;
  }

  public function editarBD($datos,$recurso, $id){
    $params = $this->generarParam($datos);
    $params = substr($params,0,1).":id,".substr($params,1);
    $sql = "SELECT editar$recurso$params";
    $d['id'] = $id;
    foreach($datos as $clave => $valor){
      $d[$clave] = $valor;
    }
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute($d);
    $res = $query->fetch(PDO::FETCH_NUM);
    $query = null;
    $con = null;
    return $res[0];
  }

  public function buscarBDA($id,$recurso){
    $sql = "CALL buscar$recurso(:id);";
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute(['id' => $id]);
    $res = $query->fetch(PDO::FETCH_ASSOC);
    $query = null;
    $con = null;
    return $res;
  }

  public function buscarBDATodo($recurso){
    $sql = "CALL buscarTodo$recurso();";
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute();
    $res = $query->fetchAll();
    $query = null;
    $con = null;
    return $res;
  }

  public function buscarBDATodoEsta($recurso){
    $sql = "CALL buscarTodoEsta$recurso();";
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute();
    $res = $query->fetchAll();
    $query = null;
    $con = null;
    return $res;
  }

  public function buscarAsig($id,$recurso){
    $sql = "CALL buscar$recurso(:id);";
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute(['id' => $id]);
    $res = $query->fetch(PDO::FETCH_ASSOC);
    $query = null;
    $con = null;
    return $res;
  }

  public function buscarBD($id,$idEmpleado,$recurso){
    $sql = "CALL buscar$recurso(:id,:idEmpleado);";
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute(['id' => $id, 'idEmpleado' => $idEmpleado]);
    $res = $query->fetch(PDO::FETCH_ASSOC);
    $query = null;
    $con = null;
    return $res;
  }

  public function eliminarBD($id,$recurso){
    $sql = "SELECT eliminar$recurso(:id);";
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute(["id" => $id]);
    $res = $query->fetch(PDO::FETCH_NUM);
    $query = null;
    $con = null;
    return $res[0];
  }

  public function filtrarBD($datos,$args,$recurso){
    $limite = $args['limite'];
    $pagina = ($args['pagina'] - 1) * $limite;
    $cadena = "";
    foreach($datos as $valor){
      $cadena .= "%$valor%&";
    }
    $sql = "call filtrar$recurso('$cadena',$pagina,$limite);";
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute();
    $res = $query->fetchAll();
   
    //para matar
    $query = null;
    $con = null;
    $datosRetorno['datos'] = $res;
    $datosRetorno['cant'] = $this->numRegsBD($datos, $recurso);
    return $datosRetorno;
  }

  public function numRegsBD($datos,$recurso){
    $cadena = "";
    foreach($datos as $valor){
      $cadena .= "%$valor%&";
    }
    $sql = "call numRegs$recurso('$cadena');";
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute();
    $res = $query->fetch(PDO::FETCH_NUM)[0];
    //para matar
    $query = null;
    $con = null;
    return $res;
  }

  public function editarUsuario(string $idUsuario, int $rol = -1, string $passwn = ''){
    $proc = $rol == -1 ? 'select passwUsuario(:id, :passw);' : "select rolUsuario(:id, :rol)";
    $sql = "call buscarUsuario(0, $idUsuario);";
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute();
    $usuario = $query->fetch(PDO::FETCH_ASSOC);
    if($usuario){
      $params = ['id' => $usuario['id']];
      $params = $rol == -1 ? array_merge($params, ['passw' => $passwn]) : array_merge($params, ['rol' => $rol]);
      $query = $con->prepare($proc);
      $retorno = $query->execute($params);
    }else{
      $retorno = false;
    }
    $query = null;
    $con = null;
    return $retorno;
  }

  public function buscarUsrBD(int $id = 0, string $idUsuario = ''){
    $con = $this->container->get('bd');
    $query = $con->prepare("CALL buscarUsuario($id, $idUsuario);");
    $query->execute();
    $res = $query->fetch();
    $query = null;
    $con = null;
    return $res;
  }

  public function buscarNombre($id, string $tipoUsuario){
    $proc = 'buscar' . $tipoUsuario . "(0,'$id')";
    $sql = "call $proc";
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute();
    if($query->rowCount() > 0){
      $res = $query->fetch(PDO::FETCH_ASSOC);
    }else{
      $res = [];
    }
    $query = null;
    $con = null;
    $res = $res['nombre'];
    if(str_contains($res, " ")){
      $res = substr($res,0,strpos($res, " "));
    }
    return $res;
  }
  
  public function accederToken(string $proc, string $idUsuario, string $tokenRef = ""){
    $sql = $proc == "modificar" ? "select modificarToken(:idUsuario, :tk);" : 
                                  "call verificarToken(:idUsuario, :tk);";
  
    $con = $this->container->get('bd');
    $query = $con->prepare($sql);
    $query->execute(['idUsuario' => $idUsuario, "tk" => $tokenRef]);
    if($proc == "modificar"){
      $datos = $query->fetch(PDO::FETCH_NUM);
    }else{
      $datos = $query->fetchColumn();
    }
    $query = null;
    $con = null;
    return $datos;
  }

}