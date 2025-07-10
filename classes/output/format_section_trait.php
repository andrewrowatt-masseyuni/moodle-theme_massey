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

/**
 * Class format_topics_rendered
 *
 * @package    theme_massey
 * @copyright  2025 Andrew Rowatt <A.J.Rowatt@massey.ac.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait format_section_trait {
    private $targetmodules = ['book', 'assign'];
    private $modules = [];
    private $moduleIDs = [];
    private $moduledata = [];

    /**
     * Render the content of a widget.
     * 
     * Based on a approach from Sébastien Viallemonteil. Ref:
     * https://moodle.org/mod/forum/discuss.php?d=435642#p1762278
     * 
     * And the Open LMS Snap theme. Ref: 
     * https://github.com/open-lms-open-source/moodle-theme_snap/blob/MOODLE_405_STABLE/classes/output/format_section_trait.php
     *
     * @param \renderable $widget The widget to render.
     * @return string The rendered content.
     */
    protected function render_content(\core_courseformat\output\local\content $widget) {
        $data = $widget->export_for_template($this); // You get an object with properties.

        if (isset($data->singlesection)) {
            self::getmodules($data->singlesection->cmlist->cms);
        } else {
            foreach ($data->sections as $section) {
                self::getmodules($section->cmlist->cms);
            }
        }

        foreach ($this->modules as $module) {
            $moduleIDs[$module->module][] = $module->id;
        }

        foreach ($moduleIDs as $module => $moduleIDlist) {
            $modmethod = "getmod_{$module}_data";
            
            if (method_exists($this,  $modmethod )) {
                call_user_func([$this, $modmethod], $moduleIDlist);
            }
        }

        // echo '<pre>'; var_dump($this->moduledata); echo '</pre>'; // Debugging line to see the module IDs.

        foreach ($this->modules as $module) {
            /** @var $module object */
            
            $modmethod = "getmod_{$module->module}_html";
            if (method_exists($this,  $modmethod )) {
                call_user_func([$this, $modmethod], $module);
            }
        }

        return $this->render_from_template($widget->get_template_name($this), $data);
    }

    private function getmodules(array $cms): void {
        // This is a helper function to get the book module from the course modules.
        // It will return the first book module found or null if none exists.
        foreach ($cms as $cmitem) {
            if (in_array($cmitem->cmitem->module, $this->targetmodules)) {
                $this->modules[] = $cmitem->cmitem;
            }
        }
    }

    function getmod_book_html(object $module): void {
        $data = $this->moduledata['book'];
        $cmid = $module->id;
        $html = '';
        if ($module->cmformat->altcontent) {
            $html .= "<div class=\"massey_booktoc-wrapper\"><div>{$module->cmformat->altcontent}</div>";
        } else {
            $html .= "<div>";
        }
        $firstchapter = true;
        $subschapter = false;

        foreach ($data as $bookchapter) {
            if ($bookchapter->cmid == $cmid) {
                if ($firstchapter) {
                    $html .= "<div class=\"massey_booktoc massey_booktoc-numbering{$bookchapter->numbering}\"><ol>";
                    $html .= "<li><a href=\"/mod/book/view.php?id={$cmid}&chapterid={$bookchapter->bookchapterid}\">" .
                        $bookchapter->chaptertitle . "</a>";
                    $firstchapter = false;
                    continue;
                }
                // $html .= "<li><a href=\"/mod/book/view.php?id={$cmid}&chapterid={$bookchapter->bookchapterid}\">" . $bookchapter->chaptertitle . "</a>";

                if (!$subschapter && $bookchapter->subchapter) {
                    $html .= "<ol><li>";
                    $subschapter = true;
                } elseif ($subschapter && !$bookchapter->subchapter) {
                    $html .= "</li></ol><li>";
                    $subschapter = false;
                } else {
                    $html .= "</li><li>";
                }

                $html .= "<a href=\"/mod/book/view.php?id={$cmid}&chapterid={$bookchapter->bookchapterid}\">" .
                    $bookchapter->chaptertitle . "</a>";
            }
        }
        $module->cmformat->altcontent = "$html</li></ol></div></div>"; // '<div class="alert alert-info">Book module link</div>';
    }

    function getmod_book_data(array $moduleIDlist): void {
        global $DB;

        list($insql, $params) = $DB->get_in_or_equal($moduleIDlist, SQL_PARAMS_NAMED);

        $sql =
            "select
            bc.id as bookchapterid, 
            cm.id as cmid,
            b.id as bookid,
            b.numbering,
            bc.pagenum,
            bc.subchapter,
            bc.title as chaptertitle
            from {course_modules} cm
            join {book} b on b.id = cm.instance
            join {book_chapters} bc on bc.bookid = b.id
            where cm.id $insql and bc.hidden = 0
            order by cm.id, bc.pagenum";
        
        $bc = $DB->get_records_sql($sql,  $params);
        $this->moduledata['book'] = $bc;      
    }

    function getmod_assign_html(object $module): void {
        $module->cmformat->dates->activitydates[] = [
            'dataid' => 'cutoffdate',
            'label' => 'Cut off:',
            'timestamp' => time(),
            'datestring' => 'today' ];
    }
}
