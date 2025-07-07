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

namespace theme_massey\output\core;

use cm_info;

/**
 * Class course_renderer
 *
 * @package    theme_massey
 * @copyright  2025 Andrew Rowatt <A.J.Rowatt@massey.ac.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_renderer extends \core_course_renderer {

    public function frontpage_section1() {
        global $SITE, $USER;

        $output = '<div>frontpage_section1</div>';

        return $output;
    }
    public function course_section_cm_list_massey($course, $section, $sectionreturn = null, $displayoptions = []) {
        $output = '<div>course_section_cm_list_massey</div>';

        return $output;
    }

    
    public function course_section_cm_list_item_massey($course,
        &$completioninfo,
        cm_info $mod,
        $sectionreturn,
        $displayoptions = []
        ) {
        $output = '<div>course_section_cm_list_item_massey</div>';

        return $output;
    }

    public function course_section_cm_text(cm_info $mod, $displayoptions = []) {
        $output = '<div>course_section_cm_text</div>';

        return $output;
    }
}
