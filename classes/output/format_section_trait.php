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

namespace theme_massey\output;
use core_courseformat\base as course_format;
use core_courseformat\output\local\content;
use theme_massey\output\core\course_renderer;

/**
 * Class format_topics_rendered
 *
 * @package    theme_massey
 * @copyright  2025 Andrew Rowatt <A.J.Rowatt@massey.ac.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait format_section_trait {
    protected function render_content(\renderable $widget) {
        $data = $widget->export_for_template($this); // You get an object with properties.
        // There you can access, add, update the $data object properties
        //var_dump($data->sections[0]->cmlist->cms[0]->cmitem);
        //debug_print_backtrace();
        // var_dump($data);
        $data->sections[0]->cmlist->cms[0]->cmitem->afterlink = '<div class="alert alert-info">Here</div>';
        //var_dump($data->sections[0]->cmlist->cms[0]->cmitem);
        //return '<div>format_topics_renderer->render_content</div>';
        return $this->render_from_template($widget->get_template_name($this), $data);
    }

}
