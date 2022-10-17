<?php

namespace App\actions;

use App\Models\User;

class Users
{
    public static function index()
    {
        $users = User::all();
        return $users;
    }

    public static function create($params)
    {
//      $user = new User();
//      $user->first_name = $params['first_name'];
//      $user->last_name = $params['last_name'];
//      $user->email = $params['email'];
//      $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
//      $user->save();
//      return $user;

        $user = new User($params);
        if (array_key_exists('password', $params)) {
            $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
        }
        $user->save();
        return $user;
    }

    public static function update($id, $params)
    {
//      $user = User::find($id);
//      if ($params['first_name']) {
//          $user->first_name = $params['first_name'];
//      }
//      if ($params['last_name']) {
//          $user->last_name = $params['last_name'];
//      }
//      if ($params['email']) {
//          $user->email = $params['email'];
//      }
//      if ($params['password']) {
//          $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
//      }
//      $user->save();
//      return $user;

        $user = User::findOrFail($id);
        $user->fill($params);
        if (array_key_exists('password', $params)) {
            $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
        }
        $user->save();

        return $user;
    }

    public static function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return false;
        }

        return $user->delete();
    }

    /*
     * s – сортировка. В значении строка в которой соединены двоеточием поле
     * по которому идет сортировка и направление сортировки (asc или desc).
     * q – ассоциативный массив. Ключ – имя поля, значение – точное значение
     * в базе данных. Поиск значений в q должен происходить по условию OR (orWhere).
     */
    public static function indexQuery($params = [])
    {
        // BEGIN (write your solution here)
        if (empty($params)) {
            return User::all();
        }
        $scope = User::query();
        if (array_key_exists('s', $params)) {
            [$fileName, $direction] = explode(":", $params['s']);
            $scope->orderBy($fileName, $direction);
        }
        if (array_key_exists('q', $params)) {
            foreach ($params['q'] as $field => $value) {
                $scope->orWhere($field, $value);
            }
        }
        return $scope->get();
    }
}
