<?php

class Database
{
   private static $dbHost = "localhost";
   private static $dbName = "webshop";
   private static $dbUser = "root";
   private static $dbPassword = "";

   private static $dbConnection = null;
   private static $dbStatement = null;
   private static $lastError = '';

   private static function connect()
   {
      if (!is_null(self::$dbConnection)) {
         return true;
      }

      try {
         $dsn = "mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName . ";charset=utf8mb4";
         $pdo = new PDO($dsn, self::$dbUser, self::$dbPassword);
         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
      } catch (PDOException $e) {
         self::$lastError = $e->getMessage();
         return false;
      }

      self::$dbConnection = $pdo;
      return true;
   }

   public static function isConnected()
   {
      return self::connect();
   }

   public static function getLastError()
   {
      return self::$lastError;
   }

   public static function setup($dbname, $user, $password)
   {
      if (!empty($dbname)) self::$dbName = $dbname;
      if (!empty($user)) self::$dbUser = $user;
      if (!empty($password)) self::$dbPassword = $password;
   }

   public static function query($query, $params = [])
   {
      if (!self::connect()) return false;

      try {
         self::$dbStatement = self::$dbConnection->prepare($query);
         self::$dbStatement->execute($params);
      } catch (PDOException $e) {
         self::$lastError = $e->getMessage();
         return false;
      }

      return true;
   }

   public static function get($return_type = PDO::FETCH_OBJ)
   {
      if (!self::connect()) return false;
      return self::$dbStatement->fetch($return_type);
   }

   public static function getAll($return_type = PDO::FETCH_OBJ)
   {
      if (!self::connect()) return false;
      return self::$dbStatement->fetchAll($return_type);
   }

   public static function lastInserted()
   {
      if (!is_null(self::$dbConnection)) {
         return self::$dbConnection->lastInsertId();
      }

      return false;
   }

   private static function destructureInsertData($data)
   {
      $column_names = '';
      $values = '';
      $first = true;

      foreach ($data as $column => $value) {
         $placeholders[':' . $column] = $value;
         $valueStr = is_int($value) ? $value : "'$value'";
         if ($first) {
            $column_names .= "`$column`";
            $values .= $valueStr;
            $first = false;
         } else {
            $column_names .= ", `$column`";
            $values .= ", $valueStr";
         }
      }

      return [
         'column_names' => $column_names,
         'values' => $values
      ];
   }

   public static function insert($table, $data)
   {
      ['column_names' => $column_names, 'values' => $values] = self::destructureInsertData($data);
      $created_at = date("Y-m-d H:i:s");

      $sql = "INSERT INTO `$table`($column_names, `created_at`, `updated_at`) VALUES($values, '$created_at', '$created_at')";

      self::query($sql);

      $lastId = self::lastInserted();
      if (self::query("SELECT * FROM `$table` WHERE `id` = $lastId")) {
         return self::get();
      }

      return false;
   }

   private static function destructureUpdateData($data)
   {
      $sql = '';
      $placeholders = [];
      $first = true;

      foreach ($data as $column => $value) {
         $sql .= ($first ? '' : ', ') . "`$column` = :$column";
         $placeholders[":$column"] = $value;
         $first = false;
      }

      return ['sql' => $sql, 'placeholders' => $placeholders];
   }

   public static function update($table, $id, $data = [])
   {
      ['sql' => $set_fields, 'placeholders' => $placeholders] = self::destructureUpdateData($data);
      $updated_at = date("Y-m-d H:i:s");

      $sql = "UPDATE `$table` SET $set_fields, `updated_at` = '$updated_at' WHERE `id` = $id";
      self::query($sql, $placeholders);

      self::query("SELECT * FROM `$table` WHERE `id` = $id");

      return self::get();
   }
}
