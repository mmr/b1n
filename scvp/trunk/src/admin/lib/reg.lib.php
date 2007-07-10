<?
/*
$Id: reg.lib.php,v 1.1.1.1 2004/01/25 15:18:51 mmr Exp $

Reg_Config Structure

Items within '[]' are optional and depends on the 'type'.

$reg_config = array(
  'Item Title'  => array(
  'reg_data'    => string,
  'db'    => string,
  'check' => string,
  'type'  => string,
  'extra' => array(
    '[seltype]' => string,
    '[value]'   => string
    '[view]'    => string,
    '[size]'    => numeric,
    '[maxlen]'  => numeric,
    '[rows]'    => numeric,
    '[cols]'    => numeric,
    '[options]' => array),
  'search'  => boolean,
  'orderby' => boolean,
  'select'  => boolean,
  'load'    => boolean,
  'mand'    => boolean));

reg_data=> name of the key on the $reg_data hash (name of the <input>).
db    => name of the column on the database.
  none:     no db column, probably a control variable (e.g. usr_passwd2)
check   => validation (content checking).
  none:     no check
  numeric:  b1n_checkNumeric
  date:     b1n_checkDate
  date_hour:  b1n_checkDateHour
  email:    b1n_checkEmail
  boolean:  b1n_checkBoolean
type  => HTML <input> Type
  none: No input at all (probably passed through hidden input or same-name-array-checkbox, hehe)
  text, password, select, radio, textarea and checkbox
extra   => Extra Args depending on type or check.
  seltype => <select> type
    Only applicable if type is 'select' and you want to use the pre-existing b1n_buildSelect functions
    date: Need the "year_start" and "year_end" in the array. 
    hour: ...
    date_hour: Need the "year_start" and "year_end" in the array. 
  view  => value of the selected index.
    Only applicable if the type is select.
  size  => <input> size
    Only applicable if type is text, password or textarea.
  maxlen  => <input> MAX Length (also used when check = length)
    Only applicable if type is text or password.
  rows  => Rows number
    Only applicable if type is textarea.
  cols  => Cols number
    Only applicable if type is textarea.
  options => radio options
    Only applicable to type radio
search  => true if wanted as search/order field.
orderby => true if can be used in "ORDER BY" part of query (ATENTION: imply in select = true) 
select  => true if wanted listed after search is performed.
load  => true if wanted on $reg_data (got on SELECT query of load function).
mand  => true if item is mandatory.
*/

function b1n_regExtract($reg_config)
{
  $reg_data = array();

  foreach($reg_config as $r)
  {
    b1n_getVar($r['reg_data'], $reg_data[$r['reg_data']]);
  }

  return $reg_data;
}

function b1n_regAdd($sql, &$ret_msgs, $reg_data, $reg_config, $table, $msg, $module_function = '')
{
  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');

  if($rs)
  {
    foreach($reg_config as $r)
    {
      $value = $reg_data[$r['reg_data']];
      $aux = '';

      // Fields
      if(b1n_cmp($r['db'], 'none'))
      {
        continue;
      }

      // Values
      switch($r['type'])
      {
      case 'select':
        switch($r['extra']['seltype'])
        {
        case 'date':
          $aux = b1n_formatDate($value);
          break;
        case 'date_hour':
          $aux = b1n_formatDateHour($value);
          break;
        case 'hour':
          $aux = b1n_formatHour($value); 
          break;
        default:
          $aux = $value;
          break;
        }
        break;
      case 'password':
        $aux = b1n_crypt($value);
        break;
      default:
        $aux = $value;
        break;
      }

      if(b1n_checkFilled($aux))
      {
        $aux = "'" . b1n_inBd($aux) . "'";

        // Setting values
        $fields[] = $r['db'];
        $values[] = $aux;
      }
    }

    $fields = implode(', ', $fields);
    $values = implode(', ', $values);

    $query = 'INSERT INTO ' . $table . ' (' . $fields . ') VALUES (' . $values . ')';

    $rs = $sql->sqlQuery($query);

    if($rs)
    {
      $aux = false;

      $query = 'SELECT @@IDENTITY AS id'; 
      $rs = $sql->sqlSingleQuery($query);

      if($rs)
      {
        $reg_data['id'] = $rs['id'];
        if(empty($module_function))
        {
          $aux = true;
        }
        else
        {
          $aux = $module_function($sql, $ret_msgs, $reg_data, $reg_config);
        }
      }
      else
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not get @@identity.');
      }

      if($aux)
      {
        b1n_retMsg($ret_msgs, b1n_SUCCESS, $msg . ' adicionado(a) com sucesso!');
        return $sql->sqlQuery('COMMIT TRANSACTION', 'trans');
      }
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not insert registry.');
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not begin transaction.');
  }

  $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
  return false; 
}

function b1n_regChange($sql, &$ret_msgs, $reg_data, $reg_config, $table, $msg, $module_function = '')
{
  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');

  if($rs)
  {
    $query = "SELECT * FROM " . $table . " WHERE " . $reg_config['ID']['db'] . " = '" . b1n_inBd($reg_data['id']) . "'";

    $update = '';
    $old_values = $sql->sqlSingleQuery($query);

    foreach($reg_config as $t => $r)
    {
      $value = $reg_data[$r['reg_data']];
      $aux = '';

      // Fields
      if(b1n_cmp($r['db'], 'none'))
      {
        continue;
      }

      // Values
      switch($r['type'])
      {
      case 'select':
        switch($r['extra']['seltype'])
        {
        case 'date':
          $aux = b1n_formatDate($value);
          $old_values[$r['db']] = b1n_formatDate(b1n_formatDateFromDb($old_values[$r['db']]));
          break;
        case 'date_hour':
          $aux = b1n_formatDateHour($value);
          $old_values[$r['db']] = b1n_formatDateHour(b1n_formatDateHourFromDb($old_values[$r['db']]));
          break;
        case 'hour':
          $aux = b1n_formatHour($value);
          $old_values[$r['db']] = b1n_formatHour(b1n_formatHourFromDb($old_values[$r['db']]));
          break;
        default:
          $aux = $value;
          break;
        }
        break;
      case 'password':
        if(empty($value))
        {
          continue(2);
        }
        $aux = b1n_crypt($value);
        break;
      default:
        $aux = $value;
        break;
      }

      // Only update if the values changed ($aux != $old...)
      if($aux != $old_values[$r['db']])
      {
        if(b1n_checkFilled($aux))
        {
          $aux = $r['db'] . " = '" . b1n_inBd($aux) . "'";
        }
        else
        {
          $aux = $r['db'] . " = NULL";
        }

        // Setting update array
        $update[] = $aux;
      }
    }

    if(is_array($update))
    {
      $update = implode(', ', $update);
      $query = "UPDATE " . $table . " SET " . $update . " WHERE " . $reg_config['ID']['db'] . " = '" . b1n_inBd($reg_data['id']) . "'";

      $rs = $sql->sqlQuery($query);
    }
    else
    {
      $rs = true;
    }

    if($rs)
    {
      $aux = true;

      if(!empty($module_function))
      {
        $reg_data['old_values'] = $old_values;
        $aux = $module_function($sql, $ret_msgs, $reg_data, $reg_config);
      }

      if($aux)
      {
        b1n_retMsg($ret_msgs, b1n_SUCCESS, $msg . ' alterado(a) com sucesso!');
        return $sql->sqlQuery('COMMIT TRANSACTION', 'trans');
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not begin transaction.');
  }

  $sql->sqlQuery('ROLLBACK TRANSACTION', 'trans');
  return false;
}

function b1n_regLoad($sql, &$ret_msgs, &$reg_data, $reg_config, $table)
{
  foreach($reg_config as $r)
  {
    if(b1n_cmp($r['db'], 'none') || !$r['load'])
    {
      continue;
    }
    $fields[] = $r['db'];
  }

  $fields = implode(', ', $fields);

  $query = "SELECT " . $fields . " FROM " . $table . " WHERE " . $reg_config['ID']['db'] . " = '" . b1n_inBd($reg_data["id"]) . "'";

  $rs = $sql->sqlSingleQuery($query);

  if(is_array($rs))
  {
    foreach($reg_config as $r)
    {
      if(b1n_cmp($r['db'], 'none') || !$r['load'])
      {
        continue;
      }

      if(b1n_cmp($r['type'], 'select'))
      {
        switch($r['extra']['seltype'])
        {
        case 'date':
        case 'date_check_exp':
        case 'date_check_dob':
          $rs[$r['db']] = b1n_formatDateFromDb($rs[$r['db']]);
          break;
        case 'date_hour':
          $rs[$r['db']] = b1n_formatDateHourFromDb($rs[$r['db']]);
          break;
        }
      }
        
      $reg_data[$r['reg_data']] = $rs[$r['db']];
    }

    $ret = true;
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'ID not Registered.');
    $ret = false;
  }

  return $ret; 
}

function b1n_regDelete($sql, &$ret_msgs, $reg_data, $reg_config, $table, $msg, $msg_plural = '', $module_function = '')
{
  $rs = $sql->sqlQuery('BEGIN TRANSACTION', 'trans');
  if($rs)
  {
    $query = 'DELETE FROM ' . $table . ' WHERE ' . $reg_config['ID']['db'] . ' IS NULL';

    foreach($reg_data['ids'] as $id)
    {
      $query .= ' OR ' . $reg_config['ID']['db'] . " = '" . b1n_inBd($id) . "'";
    }

    $rs = $sql->sqlQuery($query, 'del');

    if(sizeof($reg_data['ids']) > 1 && !empty($msg_plural))
    {
      $msg = $msg_plural;
    }

    if($rs)
    {
      $aux = true;

      if(!empty($module_function))
      {
        $aux = $module_function($sql, $ret_msgs, $reg_data, $reg_config);
      }

      if($aux)
      {
        b1n_retMsg($ret_msgs, b1n_SUCCESS, $msg . ' exclu&iacute;do(a) com sucesso!');
        return $sql->sqlQuery('COMMIT TRANSACTION', 'trans');
      }
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not Delete ' . $msg . '.');
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not Begin Transaction.');
    return false;
  }
  
  $sql->sqlQuery('ROLLBACK TRANSACTION');
  return false; 
}

function b1n_regCheck($sql, &$ret_msgs, $reg_data, $reg_config)
{
  $ret = true;

  foreach($reg_config as $t => $r)
  {
    $aux = split('[[:space:]]?&&[[:space:]]?', $r['check']);

    foreach($aux as $check)
    {
      $msg = '';
      switch($check)
      {
      case 'none':
        if($r['mand'] && empty($reg_data[$r['reg_data']]))
        {
          $msg = 'Por favor, preencha o campo <b>' . $t . '</b>.';
        }
        break;
      case 'numeric':
        if(!b1n_checkNumeric($reg_data[$r['reg_data']], $r['mand']))
        {
          $msg = '<b>' . $t . '</b> inv&aacute;lido (Apenas n&uacute;meros s&atilde;o permitidos).';
        }
        break;
      case 'date':
        if(isset($reg_data[$r['reg_data']]['month']))
        {
          if(!b1n_checkDate($reg_data[$r['reg_data']]['month'],
                    $reg_data[$r['reg_data']]['day'],
                    $reg_data[$r['reg_data']]['year'],
                    $r['mand']))
          {
            $msg = 'Data inv&aacute;lida em <b>' . $t . '</b>.';
          }
        }
        elseif($r['mand'])
        {
          $msg = 'Data inv&aacute;lida em <b>' . $t . '</b>.';
        }
        break;
      case 'date_hour':
        if(isset($reg_data[$r['reg_data']]['month']))
        {
          if(!b1n_checkDateHour($reg_data[$r['reg_data']]['month'],
                      $reg_data[$r['reg_data']]['day'],
                      $reg_data[$r['reg_data']]['year'],
                      $reg_data[$r['reg_data']]['hour'],
                      $reg_data[$r['reg_data']]['min'],
                      $r['mand']))
          {
            $msg = 'Data/Hora inv&aacute;lida em <b>' . $t . '</b>.';
          }
        }
        elseif($r['mand'])
        {
          $msg = 'Data inv&aacute;lida em <b>' . $t . '</b>.';
        }
        break;
      case 'email':
        if(!b1n_checkEmail($reg_data[$r['reg_data']]))
        {
          $msg = '<b>' . $t . '</b> inv&aacute;lido (Exemplo: usuario@dominio.org).';
        }
        break;
      case 'length':
        if(strlen(trim($reg_data[$r['reg_data']])) > $r['extra']['maxlen'])
        {
          $msg = 'N&atilde;o mais que "' . $r['extra']['maxlen'] . '" caracteres (sem espa&ccedil;os sobrantes) s&atilde;o permitidos em <b>' . $t . '</b>';
        }
        break;
      case 'exactlength':
        if(strlen(trim($reg_data[$r['reg_data']])) != $r['extra']['maxlen'])
        {
          $msg = 'Exatamente "' . $r['extra']['maxlen'] . '" caracteres (sem espa&ccedil;os sobrantes) s&atilde;o permitidos em <b>' . $t . '</b>';
        }
        break;
      case 'radio':
        if(!b1n_checkFilled($reg_data[$r['reg_data']]))
        {
          $msg = 'Por favor, escolha algo em <b>' . $t . '</b>.';
        }
        break;
      case 'boolean':
        if(!b1n_checkBoolean($reg_data[$r['reg_data']], $r['mand']))
        {
          $msg = 'Por favor, escolha algo em <b>' . $t . '</b>.';
        }
        break;
      case 'unique':
        if($r['mand'] && empty($reg_data[$r['reg_data']]))
        {
          $msg = "Por favor, preencha o campo <b>" . $t . "</b>.";
          break;
        }

        if(b1n_checkFilled($reg_data[$r['reg_data']]))
        {
          $query = "SELECT " . $reg_config['ID']['db'] . " FROM " . $r['extra']['table'] . " WHERE " . $r['db'] . " = '" . b1n_inBd($reg_data[$r["reg_data"]]) . "'";

          $rs = $sql->sqlSingleQuery($query);

          if($rs && is_array($rs))
          {
            global $page_title;
            $msg = 'J&aacute; existe um registro de ' . $page_title . ' com esse <b>' . $t . '</b>.';
            unset($page_title);
          }
        }
        break;
      case 'fk':
        if(is_array($reg_data[$r['reg_data']]))
        {
          if($r['mand'] && !sizeof($reg_data[$r['reg_data']]))
          {
            $msg = 'Por favor, selecione algo em <b>' . $t . '</b>.';
          }
        }
        else
        {
          if(!b1n_checkNumeric($reg_data[$r["reg_data"]], $r['mand']))
          {
            $msg = 'Por favor, selecione algo em <b>' . $t . '</b>.';
          }
        }
        break;
      case 'hour':
        if(!b1n_checkHour($reg_data[$r['reg_data']]['hour'], $reg_data[$r['reg_data']]['min'], $r['mand']))
        {
          $msg = 'Hora inv&aacute;lida em <b>' . $t . '</b>.';
        }
        break;
      }

      if(!empty($msg))
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, $msg);
        $ret = false;
      }
    }
  }

  return $ret;
}

function b1n_regCheckChange($sql, &$ret_msgs, $reg_data, $reg_config)
{
  $ret = true;

  foreach($reg_config as $t => $r)
  {
    $msg = '';

    switch($r['check'])
    {
    case 'none':
      if($r['mand'] && empty($reg_data[$r['reg_data']]) && $r['type'] != 'password')
      {
        $msg = 'Por favor, preencha o campo <b>' . $t . '</b>.';
      }
      break;
    case 'numeric':
      if(!b1n_checkNumeric($reg_data[$r["reg_data"]], $r['mand']))
      {
        $msg = '<b>' . $t . '</b> inv&aacute;lido (Apenas n&uacute;meros s&atilde;o permitidos).';
      }
      break;
    case 'date':
      if(!b1n_checkDate($reg_data[$r['reg_data']]['month'],
                $reg_data[$r['reg_data']]['day'],
                $reg_data[$r['reg_data']]['year'],
                $r['mand']))
      {
        $msg = 'Data invalida em <b>' . $t . '</b>.';
      }
      break;
    case 'date_hour':
      if(!b1n_checkDate($reg_data[$r['reg_data']]['month'],
                $reg_data[$r['reg_data']]['day'],
                $reg_data[$r['reg_data']]['year'],
                $reg_data[$r['reg_data']]['hour'],
                $reg_data[$r['reg_data']]['min'],
                $r['mand']))
      {
        $msg = 'Data/hora invalida em <b>' . $t . '</b>.';
      }
      break;
    case 'email':
      if(!b1n_checkEmail($reg_data[$r["reg_data"]]))
      {
        $msg = 'Invalid <b>' . $t . '</b> (Example: user@domain.org).';
      }
      break;
    case 'length':
      if(strlen($reg_data[$r['reg_data']]) > $r['extra']['maxlen'])
      {
        $msg = "No more than '" . $r["extra"]["maxlen"] . "' characters are allowed in <b>" . $t . "</b>";
      }
      break;
    case 'radio':
      if(!b1n_checkFilled($reg_data[$r['reg_data']]))
      {
        $msg = "Por favor, escolha algo em <b>" . $t . "</b>.";
      }
      break;
    case 'boolean':
      if(!b1n_checkBoolean($reg_data[$r['reg_data']], $r['mand']))
      {
        $msg = 'Por favor, escolha algo em <b>' . $t . '</b>.';
      }
      break;
    case 'unique':
      if($r['mand'] && empty($reg_data[$r['reg_data']]))
      {
        $msg = 'Por favor, preencha o campo <b>' . $t . '</b>.';
        break;
      }

      $query = "SELECT " . $reg_config['ID']['db'] . " AS id FROM " . $r['extra']['table'] . " WHERE " . $r['db'] . " = '" . b1n_inBd($reg_data[$r["reg_data"]]) . "' AND " . $reg_config['ID']['db'] . " != '" . b1n_inBd($reg_data["id"]) . "'";
      $rs = $sql->sqlSingleQuery($query);

      if(is_array($rs))
      {
        global $page_title;
        $msg = 'J&aacute; existe um registro de ' . $page_title . ' com esse <b>' . $t . '</b>.';
        unset($page_title);
      }
      break;
    case 'fk':
      if(is_array($reg_data[$r['reg_data']]) && $r['mand'] && !sizeof($reg_data[$r['reg_data']]))
      {
        $msg = 'Por favor, selecione algo em <b>' . $t . '</b>.';
      }
      else
      {
        if(!b1n_checkNumeric($reg_data[$r['reg_data']], $r['mand']))
        {
          $msg = 'Por favor, selecione algo em <b>' . $t . '</b>.';
        }
      }
      break;
    case 'hour':
      if(isset($reg_data[$r['reg_data']]['hour']))
      {
        if(!b1n_checkHour($reg_data[$r['reg_data']]['hour'], $reg_data[$r['reg_data']]['min'], $r['mand']))
        {
          $msg = 'Invalid Hour/Minute in <b>' . $t . '</b>.';
        }
      }
      break;
    }

    if(!empty($msg))
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, $msg);
      $ret = false;
    }
  }

  return $ret;
}

function b1n_regCheckDelete($sql, &$ret_msgs, $reg_data, $reg_config)
{
  if(is_array($reg_data['ids']))
  {
    return true;
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Voc&ecirc; precisa selecionar algo para ser exclu&iacute;do.');
  }

  return false;
}

function b1n_regCheckRelationship($sql, &$ret_msgs, $ids, $rel, $table, $col_id, $col_name, $msg)
{
  if(!is_array($rel))
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Rel is not an Array.');
    return false;
  }

  $ret = true;

  foreach($ids as $id)
  {
    foreach($rel as $d)
    {
      $query = '
        SELECT
          ' . $d['col_name'] . '
        FROM
          "' . $d['table'] . '"
        WHERE
          ' . $d['col_ref_id'] . ' = \'' . b1n_inBd($id) . '\'';

      $rs = $sql->sqlSingleQuery($query);

      if(is_array($rs) && sizeof($rs))
      {
        $rs2 = $sql->sqlSingleQuery('
          SELECT
            ' . $col_name . '
          FROM
            "' . $table . '"
          WHERE
            ' . $col_id . ' = \'' . b1n_inBd($id) . '\'');

        $msg = 'You cannot delete the <b><i>' . $rs2[$col_name] . '</i> ' . $msg . '</b> because it is still refered by the <b><i>' . $rs[$d['col_name']] . '</i> ' . $d['title'] . '</b>';

        if(!empty($d['as']))
        {
          $msg .= ' as <b>' . $d['as'] . '</b>';
        }

        $msg .= '.<br />Process Aborted';

        b1n_retMsg($ret_msgs, b1n_FIZZLES, $msg);
        $ret = false;
        break(2);
      }
    }
  }

  return $ret;
}

function b1n_regToggleActivation($sql, &$ret_msgs, $col_id, $id, $activate_field, $table = '')
{
  if(empty($table))
  {
    global $page;
    $table = $page;
  }

  $query = "
    UPDATE " . $table . "
    SET
      " . $activate_field . " = " . $activate_field . "^1
    WHERE
      " . $col_id . " = '" . b1n_inBd($id) . "'";

  return $sql->sqlQuery($query);
}
?>
