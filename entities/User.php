<?php
require_once 'Base.php';

class User extends Base
{

    // table columns
    public $name;
    public $email;
    public $phone1;
    public $phone2;

    public function __construct()
    {
        parent::__construct('users');
    }

    /**
     * C
     * Create user
     * @param array $params
     * @param boolean $save
     * @return boolean
     */
    public function create($params, $save = false)
    {
        if (empty($params)) {
            return false;
        }
        $this->name = isset($params['name']) ? $params['name'] : '';
        $this->email = isset($params['email']) ? $params['email'] : '';
        $this->phone1 = isset($params['phone1']) ? $params['phone1'] : '';
        $this->phone2 = isset($params['phone2']) ? $params['phone2'] : '';

        if ($save) {
            return $this->save();
        }

        return true;
    }

    public function saveReadingProgress($params)
    {
        if (empty($params) || empty($this->email)) {
            return;
        }
        $query = '';
        $lastRecord = $this->recordExists('user_readings', ['user_email' => $this->email, 'book_id' => $params['book_id']], 'page_no DESC', true);
        if ($lastRecord) {
            if (((int) $lastRecord['page_no'] < (int) $params['page_no'])) {
                $query = "UPDATE user_readings SET page_no = '{$params['page_no']}' WHERE user_email = '{$this->email}' AND book_id = '{$params['book_id']}'";
            }
        } else {
            $query = "REPLACE INTO user_readings (id, user_email, book_id, page_no) VALUES (uuid(), '{$this->email}', '{$params['book_id']}', '{$params['page_no']}')";
        }
        return $this->executeQuery($query);
    }

    protected function save()
    {
        if (empty($this->email)) {
            return [];
        }
        $query = '';
        if ($this->recordExists('users', ['email' => $this->email])) {
            // user already registered, update login timestamp
            $query = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE email = '{$this->email}'";
        } else {
            $query = "REPLACE INTO users (id, name, email, phone1, phone2) VALUES (uuid(), '{$this->name}', '{$this->email}', '{$this->phone1}', '{$this->phone2}')";
        }
        return $this->executeQuery($query);
    }
}
