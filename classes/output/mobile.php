<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Mobile output class for block_mycourses.
 *
 * @package  block_mycourses
 * @copyright 2019-onward Mike Churchward (mike.churchward@poetopensource.org)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_mycourses\output;

defined('MOODLE_INTERNAL') || die();

class mobile {

    /**
     * Returns the initial page when viewing the block for the mobile app.
     *
     * @param  array $args Arguments from tool_mobile_get_content WS
     * @return array HTML, javascript and other data
     */
    public static function mobile_view_block($args) {
        global $CFG, $PAGE, $OUTPUT;
        require_once($CFG->dirroot . '/blocks/mycourses/locallib.php');

        $args = (object) $args;

        $renderer = $PAGE->get_renderer('block_mycourses');
        $cutoffdate = time() - ($CFG->mycourses_archivecutoff * 24 * 60 * 60);
        $mycompletion = mycourses_get_my_completion();
        $inprogressview = new inprogress_view($mycompletion, $cutoffdate);
        $completedview = new completed_view($mycompletion, $cutoffdate);
        $availableview = new available_view($mycompletion, $cutoffdate);
        $data['availableview'] = $availableview->export_for_template($renderer);
        $data['inprogressview'] = $inprogressview->export_for_template($renderer);
        $data['completedview'] = $completedview->export_for_template($renderer);

        $return = [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('block_mycourses/mobile_view_block', $data)
                ],
            ],
            'otherdata' => '',
            'files' => null
        ];
        return $return;
    }
}