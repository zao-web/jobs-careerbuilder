<?php namespace JobApis\Jobs\Client\Queries;

class CareerBuilderQuery extends AbstractQuery
{
    /**
     * search
     *
     * The search query.
     *
     * @var string
     */
    protected $keywords;

    /**
     * location
     *
     * e.g., “Santa Monica, CA” or “London, UK”
     *
     * @var string
     */
    protected $location;

    /**
     * Work from Home
     *
     * @var boolean
     */
    protected $cb_workhome;

    /**
     * page
     *
     * @var integer
     */
    protected $page;

    /**
     * jobs_per_page
     *
     * @var integer
     */
    protected $jobs_per_page;

    /**
     * days_ago
     *
     * @var integer
     */
    protected $days_ago;

    /**
     * Get baseUrl
     *
     * @return  string Value of the base url to this api
     */
    public function getBaseUrl()
    {
        return 'https://careerbuilder.com/';
    }

    /**
     * Get keyword
     *
     * @return  string Attribute being used as the search keyword
     */
    public function getKeyword()
    {
        return $this->keywords;
    }

    /**
     * Required parameters
     *
     * @return array
     */
    protected function requiredAttributes()
    {
        return [
            'keywords',
        ];
    }
}
