<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Job;
use Goutte\Client;

class CareerBuilderProvider extends AbstractProvider
{

    public function getClientResponse()
    {
        // TODO: Diverting focus to get salary market data scraping done.
        // But we'll need to come back to this to properly scrape
        return parent::getClientResponse();
    }

    /**
     * TODO: Flesh out job retriever for client response.
     * Note: We'll need to get likely iterate through all Job IDs, then scrape each individual job ID.
     * Pulled directly from PR.
     */
    private function __getJobs() {

        $client = new Client();
        $crawler = $client->request('GET', SELF::CAREER_BUILDER_SITE_URL . 'jobs/?' . Arr::query($requestQuery));
        $internalResponse = $client->getInternalResponse();
        $crawler->addContent(  $internalResponse->getContent(), 'text/html; charset=utf-8');

        $crawler->filter('.job-listing-item')->each(function ($node) {

            $jobsData = $this->filterJobData( $node );
            dump( $jobsData );
        });
        die;
    }

    /**
     * Map of setter methods to query parameters
     *
     * 'emp' => [All => 'all', Full-Time => 'jtft,jtfp' ,Part-Time => 'jtpt,jtfp' ,Contractor => 'jtct,jtc2,jtcc' ,Contract to hire => 'jtch' , Intern => 'jtin,jtap', Seasonal / Temp =>'jtse,jttf,jttp', Gig Work=> 'jtfl']
     * 'posted' => //[ 24Hours => 1, 3Days => 3, 7Days => 7, 30Days => 30, 30+ Days => '']
     * 'pay' => [Any => 0, 20k+ => 20, 40k+ => 40, 60K+ => 60, 80k+ => 80, 100K+ => 100, 120k+ => 120]
     *
     * @var array
     */
    protected $queryMap = [
        'setKeywords' => 'keywords', // Job Title or Keywords
        'setLocation' => 'location', // City, State or Zip
        'setWorkFromHome' => 'cb_workhome',
        'setEmploymentType' => 'emp',
        'setDaysAgo' => 'posted',
        'setSalaryMinimum' => 'pay',
        'setEasyApply' => 'cb_apply',
        'setPage' => 'page_number',
    ];

    /**
     * Current api query parameters
     *
     * @var array
     */
    protected $queryParams = [
        'keywords' => null,
        'location' => null,
        'cb_workhome' => null,
        'emp' => null,
        'posted' => null,
        'pay' => null,
        'cb_apply' => null,
        'page_number' => null,
    ];

    /**
     * Come back to this, should be likely made part of the createJobObject method.
     */
    public function filterJobData( $node ):array {
        $job = [];
        $job['job_title'] = $node->attr('aria-label');
        $job['job_id'] = $node->attr('data-job-did');
        $job['link'] = $node->attr('href');
        // $job['is_remote'] = $node->attr('data-remote');

        return $job;
    }

     /**
     * Get job by ID.
     *
     * @param  string $id
     */
    public function getJobById($id)
    {

        $crawler = \Goutte::request( 'GET', self::CAREER_BUILDER_SITE_URL .'job/'. $id);
        $crawler->filter('#jdp_description .jdp-left-content')->each(function ($node) {
            $job = [];
            $job['desc'] = $node->html();
            self::$jobData['desc'] = $node->html();
            $node->filter('.check-bubble')->each( function( $node){
                self::$jobData['recommended_skills'][] =$node->text();

            });
        });
        dd(self::$jobData);
    }

    /**
     * Returns the standardized job object
     *
     * @param array $payload
     *
     * @return \JobApis\Jobs\Client\Job
     */
    public function createJobObject($payload)
    {
        $job = new Job([
            'title' => $payload['name'],
            'name' => $payload['name'],
            'description' => $payload['snippet'],
            'url' => $payload['url'],
            'sourceId' => $payload['id'],
            'location' => $payload['location'],
        ]);

        $job->setCompany($payload['hiring_company']['name'])
            ->setCompanyUrl($payload['hiring_company']['url'])
            ->setDatePostedAsString($payload['posted_time'])
            ->setCity($payload['city'])
            ->setState($payload['state']);

        $job->job_age = $payload['job_age'];
        $job->posted_time_friendly = $payload['posted_time_friendly'];
        $job->has_non_zr_url = $payload['has_non_zr_url'];

        return $job;
    }

    /**
     * Job response object default keys that should be set
     *
     * @return  array
     */
    public function getDefaultResponseFields()
    {
        return [
            'source',
            'id',
            'name',
            'snippet',
            'category',
            'posted_time',
            'posted_time_friendly',
            'url',
            'location',
            'city',
            'state',
            'country',
            'hiring_company',
        ];
    }

    /**
     * Get listings path
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return 'jobs';
    }

    /**
     * Get listing path
     *
     * @return  string
     */
    public function getListingPath($id)
    {
        return 'job/' . $id;
    }
}
