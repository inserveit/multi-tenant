<?php

/*
 * This file is part of the hyn/multi-tenant package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/hyn/multi-tenant
 */

namespace Hyn\Tenancy\Traits;

use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Database\Connection;
use Illuminate\Database\ConnectionResolverInterface as Resolver;

trait MutatesSeedCommands
{
    use AddWebsiteFilterOnCommand;
    /**
     * @var WebsiteRepository
     */
    private $websites;
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Resolver $resolver)
    {
        parent::__construct($resolver);

        $this->setName('tenancy:' . $this->getName());
        $this->addOption(
            'website_id',
            null,
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
            'The tenancy website_ids (not uuid) to migrate specifically.'
        );

        $this->websites = app(WebsiteRepository::class);
        $this->connection = app(Connection::class);
    }

    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        $this->input->setOption('force', true);
        $this->input->setOption('database', $this->connection->tenantName());

        $this->processHandle();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        foreach ($options = parent::getOptions() as $i => $option) {
            if ($option[0] === 'class') {
                $option[4] = config('tenancy.db.tenant-seed-class', false) ?: $option[4];

                $options[$i] = $option;
            }
        }

        return array_merge($options, [
            $this->addWebsiteOption()
        ]);
    }
}
