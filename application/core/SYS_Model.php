<?php

class SYS_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
    }

    /**
     * Get Data from the database
     * @access	public
     * @param	string	the table name
     * @param	string	the select keys
     * @param	array	where condition values
     * @param	string	order condition value
     * @return	string
     */
    function getList($table, $select = "*", $param = array(), $orderBy = "", $limit = "", $offset = "") {
        $this->db->select("{$select}");

        if (!empty($orderBy)) {
            $this->db->order_by($orderBy);
        }
        if ($limit != "" && $offset != "") {
            $this->db->limit($limit, $offset);
        }

        if (sizeof($param) == 0) {
            $query = $this->db->get($table);
        } else {
            $query = $this->db->get_where($table, $param);
        }
        return $query->result();
    }

    /**
     * Get a single row of data from the database
     * @access	public
     * @param	string	the table name
     * @param	string	the select keys
     * @param	array	where condition values
     * @param	string	order condition value
     * @return	string
     */
    function getRow($table, $select = "*", $param = array(), $orderBy = "") {
        $this->db->select("{$select}");

        if (!empty($orderBy)) {
            $this->db->order_by($orderBy);
        }

        if (sizeof($param) == 0) {
            $query = $this->db->get($table);
        } else {
            $query = $this->db->get_where($table, $param);
        }

        return $query->row();
    }

    /**
     * Insert Data into the database
     * @access	public
     * @param	string	the table name	 
     * @param	array	the insert values
     * @return	string
     */
    function create($table, $params, $logUserInfo = TRUE) {
        if ($logUserInfo) {
            $params['created_by'] = $this->user_id ;
            $this->db->set('created_on', 'NOW()', FALSE);
            $this->db->set('last_modified_on', 'NOW()', FALSE);
            $params['last_modified_by'] = $this->user_id ;
        }
        $this->db->insert($table, $params);
       
        return $this->db->insert_id();
    }

    /**
     * Update Data in the database
     *
     * Soft Delete Only
     * @access	public
     * @param	string	the table name
     * @param	array	the update value
     * @param	array	where condition values
     * @return	string
     */
    function updateTableRow($table, $params, $condition, $logUserInfo = TRUE) {
        if ($logUserInfo) {
            $this->db->set('last_modified_on', 'NOW()', FALSE);
            $params['last_modified_by'] = $this->user_id ;
        }

        $this->db->where($condition);
        $this->db->update($table, $params);
        return $this->db->affected_rows();
    }

    /**
     * Remove Data from the database 
     * Soft Delete Only
     * @access	public
     * @param	string	the table name
     * @param	array	the update value
     * @param	array	where condition values
     * @return	string
     */
//    function delete($table, $params, $condition) {
//        $this->db->where($condition);
//        $this->db->update($table, $params);
//        return $this->db->affected_rows();
//    }

    /**
     * "Count Rows with condition" query
     * Generates a platform-specific query string that counts all records in
     * the specified database with a specified where condition
     *
     * @access	public
     * @param	string
     * @return	string
     */
    function count_all_rows($table = '', $params = '', $orWhere = '') {
        $this->db->select('COUNT(*) AS numrows');
        $this->db->from($table);
        if ($orWhere)
            $this->db->or_where($orWhere);
        if ($params)
            $this->db->where($params);
        return $this->db->get()->row()->numrows;
    }

    /**
     * Remove Data from the database
     * Hard Delete
     * @access	public
     * @param	string	the table name
     * @param	array	the update value
     * @param	array	where condition values
     * @return	string
     */
    function remove($table, $condition) {
        $this->db->delete($table, $condition);
        return $this->db->affected_rows();
    }

    /**
     * Remove Data from the database
     *
     * Hard Delete
     * @access	public
     * @param	string	the table name
     * @param	array	the update value
     * @param	array	where condition values
     * @return	string
     */
    function delete_entry($table, $condition) {
        $this->db->delete($table, $condition);
        return $this->db->affected_rows();
    }

}

