<?php
function arrayToTable($data) {
    if (empty($data) || !is_array($data)) {
        return '<p>Veri bulunamadı veya geçersiz veri tipi.</p>';
    }

    $html = '<table border="1" style="border-collapse: collapse; width: 100%;">';
    
    // Header row with "Key" and "Value"
    $html .= '<thead><tr>';
    $html .= '<th style="padding: 8px; background-color: #f2f2f2;">Key</th>';
    $html .= '<th style="padding: 8px; background-color: #f2f2f2;">Value</th>';
    $html .= '</tr></thead>';
    
    // Data rows
    $html .= '<tbody>';
    
    // Check if data is a 1D associative array or a 2D array
    $isSingleRow = !isset($data[0]) || !is_array($data[0]);
    
    if ($isSingleRow) {
        // 1D array: each key-value pair is a row
        foreach ($data as $key => $value) {
            $html .= '<tr>';
            $html .= '<td style="padding: 8px;">' . htmlspecialchars($key) . '</td>';
            $html .= '<td style="padding: 8px;">' . htmlspecialchars($value) . '</td>';
            $html .= '</tr>';
        }
    } else {
        // 2D array: flatten by showing each row's key-value pairs
        foreach ($data as $rowIndex => $row) {
            if (!is_array($row)) continue; // Skip invalid rows
            foreach ($row as $key => $value) {
                $html .= '<tr>';
                $html .= '<td style="padding: 8px;">' . htmlspecialchars($key) . '</td>';
                $html .= '<td style="padding: 8px;">' . htmlspecialchars($value) . '</td>';
                $html .= '</tr>';
            }
        }
    }
    
    $html .= '</tbody>';
    $html .= '</table>';
    
    return $html;
}

/**
 * Bir diziyi (1D veya 2D) HTML tablosuna dönüştürür.
 *
 * @param array $array Dönüştürülecek dizi.
 * @param string $tableAttributes Tablo etiketi için CSS class'ı veya style gibi ek özellikler.
 * @return string Oluşturulan HTML tablo kodu.
 */
function arrayToHtmlTable(array $array, string $tableAttributes = 'border="1" style="border-collapse: collapse; width: 50%;"'): string
{
    // Dizi boşsa bir mesaj döndür
    if (empty($array)) {
        return "<p>Dizi boş.</p>";
    }

    $html = "<table {$tableAttributes}>";

    // Dizinin 1D mi yoksa 2D mi olduğunu kontrol et
    // Bunu yapmak için ilk öğenin bir dizi olup olmadığına bakarız
    $firstElement = current($array);

    if (is_array($firstElement)) {
        // --- 2D Dizi (Tablo) ---
        // Kural: Sütun isimleri key'ler, satırlar value'lar olsun.
        // Farklı satırlarda farklı key'ler olabileceği için önce tüm key'leri toplamalıyız.
        
        $headers = [];
        foreach ($array as $row) {
            if (is_array($row)) {
                $headers = array_merge($headers, array_keys($row));
            }
        }
        // Benzersiz başlıkları al
        $headers = array_unique($headers);

        // 1. Başlık (Header) Satırını (<th>) Oluştur
        $html .= "<thead><tr>";
        foreach ($headers as $header) {
            $html .= "<th>" . htmlspecialchars($header) . "</th>";
        }
        $html .= "</tr></thead>";

        // 2. Veri Satırlarını (<td>) Oluştur
        $html .= "<tbody>";
        foreach ($array as $row) {
            $html .= "<tr>";
            if (is_array($row)) {
                // Her başlık için bu satırda bir değer ara
                foreach ($headers as $header) {
                    // Eğer bu satırda o başlığa ait bir key varsa değerini yaz, yoksa boş bırak
                    $value = isset($row[$header]) ? $row[$header] : '';
                    $html .= "<td>" . htmlspecialchars($value) . "</td>";
                }
            } else {
                // Dizi 2D olarak başladı ama bu eleman bir dizi değilse (karışık dizi)
                $html .= "<td colspan='" . count($headers) . "'>" . htmlspecialchars($row) . "</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</tbody>";

    } else {
        // --- 1D Dizi (Key-Value) ---
        // Kural: "key" ve "value" isminde iki sütun olsun.
        
        // 1. Başlık (Header) Satırını Oluştur
        $html .= "<thead><tr>";
        $html .= "<th>key</th>";
        $html .= "<th>value</th>";
        $html .= "</tr></thead>";

        // 2. Veri Satırlarını Oluştur
        $html .= "<tbody>";
        foreach ($array as $key => $value) {
            // Değerin kendisi bir dizi ise (örn: 3D dizi), hatayı önle
            if (is_array($value)) {
                $value = '[Array]';
            }
            
            $html .= "<tr>";
            $html .= "<td>" . htmlspecialchars($key) . "</td>";
            $html .= "<td>" . htmlspecialchars($value) . "</td>";
            $html .= "</tr>";
        }
        $html .= "</tbody>";
    }

    $html .= "</table>";
    return $html;
}

?>