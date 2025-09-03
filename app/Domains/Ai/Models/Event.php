<?php

namespace App\Domains\Ai\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_name',
        'date',
        'time',
        'venue',
        'organizer',
        'description',
    ];

    /**
     * Parse description into structured sections
     */
    public function getParsedDescriptionAttribute()
    {
        if (!$this->description) {
            return [
                'programme' => null,
                'speakers' => [],
                'agenda' => [],
                'cpd_points' => null,
                'summary' => 'No description available'
            ];
        }

        $text = $this->description;
        $result = [
            'programme' => null,
            'speakers' => [],
            'agenda' => [],
            'cpd_points' => null,
            'summary' => ''
        ];

        // Extract Programme
        if (preg_match('/Programme:\s*(.+?)(?=\n\n|\nSpeakers:|\nCPD|\nAgenda:|$)/is', $text, $match)) {
            $result['programme'] = trim($match[1]);
        }

        // Extract CPD Points
        if (preg_match('/CPD Points?:\s*(.+?)(?=\n\n|\nSpeakers:|\nProgramme:|\nAgenda:|$)/is', $text, $match)) {
            $result['cpd_points'] = trim($match[1]);
        }

        // Extract Speakers block
        if (preg_match('/Speakers?:\s*(.+?)(?=\n\nCPD|\n\nAgenda:|\n\nProgramme:|$)/is', $text, $match)) {
            $speakersBlock = trim($match[1]);
            $speakers = preg_split('/[,\n]+/', $speakersBlock);
            $result['speakers'] = array_values(array_filter(array_map('trim', $speakers)));
        }

        // Extract Agenda block
        if (preg_match('/Agenda:\s*(.+)/is', $text, $match)) {
            $agendaBlock = trim($match[1]);
            $agenda = preg_split('/\n+/', $agendaBlock);
            $result['agenda'] = array_values(array_filter(array_map('trim', $agenda)));
        }

        // Create summary for card display
        $summaryParts = [];
        if ($result['programme']) {
            $summaryParts[] = $result['programme'];
        }
        if ($result['cpd_points']) {
            $summaryParts[] = "CPD: " . $result['cpd_points'];
        }
        if (!empty($result['speakers'])) {
            $speakerCount = count($result['speakers']);
            $summaryParts[] = "{$speakerCount} speaker" . ($speakerCount > 1 ? 's' : '');
        }
        
        $result['summary'] = implode(' • ', $summaryParts) ?: 'Event details available';

        return $result;
    }

    /**
     * Get short summary for card display
     */
    public function getSummaryAttribute()
    {
        return $this->parsed_description['summary'];
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];
}
