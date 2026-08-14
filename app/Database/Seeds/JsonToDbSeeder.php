<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JsonToDbSeeder extends Seeder
{
    public function run()
    {
        // 1. Site Banners
        $this->seedFromJson('site_banners.json', 'site_banners');
        
        // 2. Procurements
        $this->seedFromJson('procurement_items.json', 'procurements');
        
        // 3. ITA Documents
        $this->seedFromJson('ita_items.json', 'ita_documents');
        
        // 4. Executives
        $this->seedFromJson('site_executives.json', 'executives');
        
        // 5. Nora Knowledge
        $this->seedFromJson('nora_ai_knowledge.json', 'nora_knowledge');
        
        // 6. Gallery Albums (Special handling needed if format differs, but let's try direct map)
        $this->seedFromJson('gallery_albums.json', 'gallery_albums');
    }

    private function seedFromJson($jsonFile, $tableName)
    {
        $filePath = WRITEPATH . $jsonFile;
        if (!file_exists($filePath)) {
            echo "Skipping {$jsonFile} - file not found.\n";
            return;
        }

        $json = file_get_contents($filePath);
        $data = json_decode($json, true);

        if (!empty($data) && is_array($data)) {
            if (isset($data['id'])) {
                $data = [$data]; // Wrap single record in array
            }

            foreach ($data as &$row) {
                $createdAt = date('Y-m-d H:i:s');
                $updatedAt = date('Y-m-d H:i:s');
                
                // Copy row for manipulation
                $dbRow = $row;
                $dbRow['created_at'] = $createdAt;
                
                if ($tableName !== 'gallery_photos') {
                    $dbRow['updated_at'] = $updatedAt;
                }

                // ------------------------------------
                // MAPPINGS
                // ------------------------------------
                if ($tableName === 'procurements') {
                    if (isset($dbRow['date'])) { $dbRow['published_date'] = $dbRow['date']; unset($dbRow['date']); }
                    if (isset($dbRow['attachment_url'])) { $dbRow['doc_path'] = $dbRow['attachment_url']; unset($dbRow['attachment_url']); }
                    unset($dbRow['views'], $dbRow['active'], $dbRow['id']);
                    if (isset($row['active'])) { $dbRow['status'] = $row['active'] ? 'active' : 'inactive'; }
                    if (isset($dbRow['budget'])) {
                        $dbRow['budget'] = (float) preg_replace('/[^0-9.]/', '', $dbRow['budget']);
                    }
                    $this->db->table($tableName)->ignore(true)->insert($dbRow);
                } 
                elseif ($tableName === 'ita_documents') {
                    if (isset($dbRow['code'])) { $dbRow['oit_code'] = $dbRow['code']; unset($dbRow['code']); }
                    if (isset($dbRow['title'])) { $dbRow['name'] = $dbRow['title']; unset($dbRow['title']); }
                    if (isset($dbRow['file_url'])) { $dbRow['url'] = $dbRow['file_url']; unset($dbRow['file_url']); }
                    unset($dbRow['category'], $dbRow['sub_category'], $dbRow['desc'], $dbRow['file_type'], $dbRow['file_size'], $dbRow['downloads'], $dbRow['featured'], $dbRow['verified'], $dbRow['date'], $dbRow['id']);
                    $this->db->table($tableName)->ignore(true)->insert($dbRow);
                }
                elseif ($tableName === 'executives') {
                    if (isset($dbRow['photo'])) { $dbRow['image_path'] = $dbRow['photo']; unset($dbRow['photo']); }
                    unset($dbRow['category'], $dbRow['quote'], $dbRow['phone'], $dbRow['email'], $dbRow['featured'], $dbRow['id']);
                    $this->db->table($tableName)->ignore(true)->insert($dbRow);
                }
                elseif ($tableName === 'nora_knowledge') {
                    if (isset($dbRow['question'])) { $dbRow['intent'] = $dbRow['question']; unset($dbRow['question']); }
                    if (isset($dbRow['answer'])) { $dbRow['answer_text'] = $dbRow['answer']; unset($dbRow['answer']); }
                    if (isset($dbRow['link_url'])) { $dbRow['action_link'] = $dbRow['link_url']; unset($dbRow['link_url']); }
                    unset($dbRow['link_title'], $dbRow['id']);
                    $this->db->table($tableName)->ignore(true)->insert($dbRow);
                }
                elseif ($tableName === 'site_banners') {
                    unset($dbRow['id']); // Let auto-increment handle it
                    $this->db->table($tableName)->ignore(true)->insert($dbRow);
                }
                elseif ($tableName === 'gallery_albums') {
                    $photos = $dbRow['photos'] ?? [];
                    unset($dbRow['category'], $dbRow['date'], $dbRow['views'], $dbRow['active'], $dbRow['photos'], $dbRow['id']);
                    
                    $this->db->table($tableName)->insert($dbRow);
                    $albumId = $this->db->insertID();

                    if ($albumId && !empty($photos)) {
                        foreach ($photos as $photoUrl) {
                            $this->db->table('gallery_photos')->insert([
                                'album_id' => $albumId,
                                'image_path' => $photoUrl,
                                'created_at' => $createdAt
                            ]);
                        }
                    }
                }
            }
            echo "Migrated data from {$jsonFile} to {$tableName}\n";
        }
    }
}
