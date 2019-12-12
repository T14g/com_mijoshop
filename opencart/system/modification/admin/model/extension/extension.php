<?php
class ModelExtensionExtension extends Model {
	public function getInstalled($type) {
		$extension_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY code");

		foreach ($query->rows as $result) {
			$extension_data[] = $result['code'];
		}

		return $extension_data;
	}


    public function getExtensions($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "extension WHERE extension_id <> 0";
            if (!empty($data['filter_type'])) {
                $sql .= " AND type = '" . $this->db->escape($data['filter_type']) . "'";
            }
            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }
                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }
                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }
            $extension_data = $this->db->query($sql)->rows;
            return $extension_data;
        } else {
            $sql = "SELECT * FROM " . DB_PREFIX . "extension ORDER BY code";
            $extension_data = $this->db->query($sql)->rows;
            return $extension_data;
        }
    }

	public function install($type, $code) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = '" . $this->db->escape($type) . "', `code` = '" . $this->db->escape($code) . "'");
	}

	public function uninstall($type, $code) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
	}
}