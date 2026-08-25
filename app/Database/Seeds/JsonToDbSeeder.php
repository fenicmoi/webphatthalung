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
                    $insertData = [
                        'title'          => $dbRow['title'] ?? '',
                        'budget'         => isset($dbRow['budget']) ? (float) preg_replace('/[^0-9.]/', '', (string)$dbRow['budget']) : 0,
                        'method'         => $dbRow['method'] ?? null,
                        'category'       => $dbRow['category'] ?? 'ประกาศจัดซื้อจัดจ้าง',
                        'status'         => isset($row['active']) ? ($row['active'] ? 'active' : 'inactive') : ($dbRow['status'] ?? 'active'),
                        'doc_path'       => $dbRow['attachment_url'] ?? ($dbRow['doc_path'] ?? null),
                        'published_date' => $dbRow['date'] ?? ($dbRow['published_date'] ?? null),
                        'created_at'     => $createdAt,
                        'updated_at'     => $updatedAt,
                    ];
                    $this->db->table($tableName)->ignore(true)->insert($insertData);
                } 
                elseif ($tableName === 'ita_documents') {
                    $insertData = [
                        'oit_code'   => $dbRow['code'] ?? ($dbRow['oit_code'] ?? null),
                        'name'       => $dbRow['title'] ?? ($dbRow['name'] ?? ''),
                        'url'        => $dbRow['file_url'] ?? ($dbRow['url'] ?? null),
                        'year'       => $dbRow['year'] ?? date('Y'),
                        'status'     => 'active',
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                    ];
                    $this->db->table($tableName)->ignore(true)->insert($insertData);
                }
                elseif ($tableName === 'executives') {
                    $insertData = [
                        'name'       => $dbRow['name'] ?? '',
                        'position'   => $dbRow['position'] ?? null,
                        'image_path' => $dbRow['photo'] ?? ($dbRow['image_path'] ?? null),
                        'order_num'  => $dbRow['order_num'] ?? 0,
                        'active'     => isset($dbRow['active']) ? ($dbRow['active'] ? 1 : 0) : 1,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                    ];
                    $this->db->table($tableName)->ignore(true)->insert($insertData);
                }
                elseif ($tableName === 'nora_knowledge') {
                    $insertData = [
                        'intent'      => $dbRow['question'] ?? ($dbRow['intent'] ?? ''),
                        'keywords'    => $dbRow['keywords'] ?? null,
                        'answer_text' => $dbRow['answer'] ?? ($dbRow['answer_text'] ?? ''),
                        'action_link' => $dbRow['link_url'] ?? ($dbRow['action_link'] ?? null),
                        'created_at'  => $createdAt,
                        'updated_at'  => $updatedAt,
                    ];
                    $this->db->table($tableName)->ignore(true)->insert($insertData);
                }
                elseif ($tableName === 'site_banners') {
                    $insertData = [
                        'title'             => $dbRow['title'] ?? null,
                        'subtitle'          => $dbRow['subtitle'] ?? null,
                        'badge_title'       => $dbRow['badge_title'] ?? null,
                        'badge_icon'        => $dbRow['badge_icon'] ?? null,
                        'bg_type'           => $dbRow['bg_type'] ?? 'image',
                        'image_path'        => $dbRow['image_path'] ?? null,
                        'floating_img_path' => $dbRow['floating_img_path'] ?? null,
                        'floating_pos'      => $dbRow['floating_pos'] ?? null,
                        'floating_anim'     => $dbRow['floating_anim'] ?? null,
                        'card_placement'    => $dbRow['card_placement'] ?? null,
                        'desc'              => $dbRow['desc'] ?? null,
                        'button_text'       => $dbRow['button_text'] ?? null,
                        'button_url'        => $dbRow['button_url'] ?? null,
                        'button_icon'       => $dbRow['button_icon'] ?? null,
                        'style_class'       => $dbRow['style_class'] ?? null,
                        'active'            => isset($dbRow['active']) ? ($dbRow['active'] ? 1 : 0) : 1,
                        'created_at'        => $createdAt,
                        'updated_at'        => $updatedAt,
                    ];
                    $this->db->table($tableName)->ignore(true)->insert($insertData);
                }
                elseif ($tableName === 'gallery_albums') {
                    $photos = $dbRow['photos'] ?? [];
                    $insertData = [
                        'title'       => $dbRow['title'] ?? '',
                        'cover_image' => $dbRow['cover_image'] ?? null,
                        'description' => $dbRow['description'] ?? null,
                        'created_at'  => $createdAt,
                        'updated_at'  => $updatedAt,
                    ];
                    
                    $this->db->table($tableName)->insert($insertData);
                    $albumId = $this->db->insertID();

                    if ($albumId && !empty($photos)) {
                        foreach ($photos as $photoUrl) {
                            $this->db->table('gallery_photos')->insert([
                                'album_id'   => $albumId,
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
