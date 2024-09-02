<?php

namespace App\Http\Controllers\admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Keyword;
use App\Models\Question;
use App\Models\Sitemap;
use DOMAttr;
use DOMDocument;
use DOMElement;
use DataTables;


class QuestionController extends Controller
{
    public $route = 'admin/question';
    public $view  = 'admin/question.';
    public $moduleName = 'Question';

    public function index()
    {
        $moduleName = $this->moduleName;
        return view($this->view . 'index', compact('moduleName'));
    }
    public function getData()
    {
        $data = Question::get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editUrl = '';
                $deleteUrl = '';
                $statusUrl = '';
                $btn = '';
                $btn .= '<a href="' . $editUrl . '" class="edit btn btn-primary btn-sm" style="margin-left:5px;"><i class="fa fa-pencil"> </i> Edit</a>';


                if ($row->is_active == 1) {
                    $btn .= '<a href="' . $statusUrl . '" class="edit btn btn-success btn-sm" style="margin-left:5px;"><i class="fa fa-check"> </i> Inactive</a>';
                } else {
                    $btn .= '<a href="' . $statusUrl . '" class="edit btn btn-danger btn-sm" style="margin-left:5px;"><i class="fa fa-check" > </i> Active</a>';
                }

                $btn .= '<a href="' . $deleteUrl . '" class="edit btn btn-danger btn-sm" style="margin-left:5px;"> <i class="fa fa-trash" /> </i> Delete</a>';
                // return $btn;
                return 'Action';
            })

            ->editColumn('answer', function ($row) {
                return $row->answer_status === 1 ? 'Yes' : 'No';
            })
            ->editColumn('sitemap', function ($row) {
                return $row->sitemap === 1 ? 'Yes' : 'No';
            })

            ->rawColumns(['action'])
            ->make(true);
    }



    public static function searchQuestion()
    {

        $keyword = Keyword::where('is_active', 1)->where('has_more', 1)->inRandomOrder()->first();
        $curl = curl_init();

        $search_keyword = urlencode($keyword->name);
        $page_size = "100";
        curl_setopt_array($curl, array(
            // CURLOPT_URL => "https://api.stackexchange.com/2.3/search?pagesize=50&page=" . $keyword->page_no . "&order=desc&site=stackoverflow&key=78rjZg2x1Zo7pSqooZLrcg((&sort=relevance&intitle=" . $search_keyword . "&filter=!nKzQUR3Egv",
            CURLOPT_URL => "http://www.viewtutorial.tk/question.php?page_size=" . $page_size . "&page_no=" . $keyword->page_no . "&intitle=" . $search_keyword . "",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(),
        ));

        $response = curl_exec($curl);
        // dd($response);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $results =  json_decode($response);
            if (isset($results)) {
                foreach ($results->items as $result) {
                    echo '==Question Fetched->' . $result->question_id . '==';
                    $tags = implode(',', $result->tags);
                    $data_added = Question::updateOrCreate(['question_id' => $result->question_id], ['question_id' => $result->question_id, 'tags' => $tags, 'title' => $result->title, 'body' => $result->body, 'slug' => Str::slug($result->title), 'question_link' => $result->link, 'created_by' => 1]);
                }
                Keyword::find($keyword->id)->update(['has_more' => $results->has_more, 'page_no' => ($keyword->page_no + 1)]);
            }
        }
    }

    public static function searchAnswer()
    {

        $questions = Question::where('answer_status', 0)
            ->limit(100)
            ->get();
        foreach ($questions as $question) {
            sleep(1);
            echo '==Answer Fetched->' . $question->question_id . '==';
            $results = Helper::searchAnswer($question->question_id);

            foreach ($results->items as $result) {
                Answer::updateOrCreate(['answer_id' => $result->answer_id], ['question_id' => $question->id, 'answer_id' => $result->answer_id, 'body' => $result->body, 'created_by' => 1]);
            }

            Question::find($question->id)->update(['answer_status' => 1]);
        }
    }

    public static function createSitemap()
    {
        $sitemapLimit = 20;
        $checkCount = Question::where('sitemap', 0)
            ->where('answer_status', 1)
            ->count();
        Helper::createSitemap($checkCount, $sitemapLimit);
    }
}
