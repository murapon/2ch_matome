<?php
class DBConnection extends PDO {

    /**
     * @var bool $con 接続フラグ
     */
    private static $con = null;

    /**
     * コンストラクタ
     *
     * @param string $dsnKey   接続先データベース情報
     * @param string $user     ユーザー
     * @param string $password パスワード
     */
    public function __construct($dsnKey, $user, $password) {
        try {
            parent::__construct($dsnKey, $user, $password, array(
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                ));
        }
        catch (PDOException $e) {
            exit();
        }
    }

    /**
     * 接続し、インスタンスを返す
     *
     * @return PDO 接続インスタンス
     */
    public static function getConnection() {
        if (!self::$con) {
            $dsnKey   = DB_DSN;
            $user     = DB_USER;
            $password = DB_PASSWORD;
            self::$con = new DBConnection($dsnKey, $user, $password);
        }
        return self::$con;
    }

    public static function begin() {
        self::getConnection();
        if(self::$con != null){
            self::$con->beginTransaction();
        }
    }

    public static function db_commit(){
        if(self::$con != null){
            self::$con->commit();
        }
    }

    public static function db_rollBack() {
        if(self::$con != null){
            self::$con->rollBack();
        }
    }
}
?>