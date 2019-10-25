<?php

/*
 * Project: Cleanbox
 * Document: Timeline
 * 
 * @author Manevi Matias Alejandro 
 * Informatic Engineer - Web Developer
 * 
 * Contact: manevimatias@gmail.com
 * 
 * All rights reserved ®
 */

class TimelineService {
    
    public static function createTimeline($property_id){
        $instance = &get_instance();
        return $instance->basic->save('properties_timeline', 'id', [
            'property_id' => $property_id
        ]);
    } 

    public static function createEvent($data, $pictures = []){
        $instance = &get_instance();

        $event_id = $instance->basic->save('timeline_events', 'id', $data);

        if(!empty($pictures)){
            // Create event pictures
            foreach ($pictures as $picture) {
               $instance->basic->save('event_pictures', 'id', [
                   'event_id' => $event_id,
                   'url' => $picture,
               ]); 
            }
        }
    }

    public static function get($property_id = false) {
        $instance = &get_instance();
        $timeline = [];

        if($property_id){
            $timelines = $instance->basic->get_where('properties_timeline', array('property_id' => $property_id))->row_array();
            if($timelines){
                $events = $instance->basic->get_where('timeline_events', array('timeline_id' => $timelines['id']), 'created_at', 'DESC')->result_array();
            }
        }else{
            $events = $instance->basic->get_where('timeline_events', array(), 'created_at', 'DESC')->result_array();
        }

        $result = [];

        if(isset($events)){

            foreach ($events as $event) {
                $event['date'] = date('d-m-Y H:i', strtotime($event['created_at']));
                $event['pictures'] = $instance->basic->get_where('event_pictures', array('event_id' => $event['id']))->result_array();
                array_push($timeline, $event);
            }

            $years = self::getYears($timeline);
            
            foreach ($years as $year) {
                $result[$year] = [];

                foreach ($timeline as $event) {
                    $event_year = date('Y', strtotime($event['created_at']));

                    if ($year == $event_year) {
                        if(empty($result[$year])){
                            $event['year'] = $event_year;
                        }
                        array_push($result[$year], $event);
                    }
                }
            }
        }

        return $result;
    }

    public static function getYears($timeline) {
        $years = [];
       
        foreach ($timeline as $event) {
            $year = date('Y', strtotime($event['created_at']));

            if (!in_array($year, $years)) {
                array_push($years, $year);
            }
        }

        return $years;
    }

}