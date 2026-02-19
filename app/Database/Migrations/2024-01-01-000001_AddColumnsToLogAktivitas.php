<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnsToLogAktivitas extends Migration
{
    public function up()
    {
        // Cek apakah kolom sudah ada sebelum menambahkan
        $fields = $this->db->getFieldNames('log_aktivitas');
        
        // Tambahkan kolom ip_address jika belum ada
        if (!in_array('ip_address', $fields)) {
            $this->forge->addColumn('log_aktivitas', [
                'ip_address' => [
                    'type' => 'VARCHAR',
                    'constraint' => 45,
                    'null' => true,
                    'after' => 'aktivitas'
                ]
            ]);
        }
        
        // Tambahkan kolom user_agent jika belum ada
        if (!in_array('user_agent', $fields)) {
            $this->forge->addColumn('log_aktivitas', [
                'user_agent' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'ip_address'
                ]
            ]);
        }
        
        // Tambahkan kolom method jika belum ada
        if (!in_array('method', $fields)) {
            $this->forge->addColumn('log_aktivitas', [
                'method' => [
                    'type' => 'VARCHAR',
                    'constraint' => 10,
                    'null' => true,
                    'after' => 'user_agent'
                ]
            ]);
        }
        
        // Tambahkan kolom url jika belum ada
        if (!in_array('url', $fields)) {
            $this->forge->addColumn('log_aktivitas', [
                'url' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'method'
                ]
            ]);
        }
    }

    public function down()
    {
        // Hapus kolom jika rollback
        $fields = $this->db->getFieldNames('log_aktivitas');
        
        if (in_array('url', $fields)) {
            $this->forge->dropColumn('log_aktivitas', 'url');
        }
        
        if (in_array('method', $fields)) {
            $this->forge->dropColumn('log_aktivitas', 'method');
        }
        
        if (in_array('user_agent', $fields)) {
            $this->forge->dropColumn('log_aktivitas', 'user_agent');
        }
        
        if (in_array('ip_address', $fields)) {
            $this->forge->dropColumn('log_aktivitas', 'ip_address');
        }
    }
}
