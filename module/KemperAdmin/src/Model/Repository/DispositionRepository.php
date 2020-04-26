<?php


namespace KemperAdmin\Model\Repository;

use App\Model\Repository\AbstractRepository;
class DispositionRepository extends AbstractRepository
{
    public function getDispositions()
    {
        $query = <<<SQL
select description from Dispositions order by description
SQL;
        return $this->executeQuery($query);
    }

    public function getDispositionStats()
    {
        $query = <<<SQL
select  description,
        count(Bill190.disposition_id) as [disposition count] 
        from Bill190 left join Dispositions 
        on Bill190.disposition_id = Dispositions.Disposition_id 
        where description is not null 
        group by Bill190.disposition_id,
        [description] 
        order by [description]
SQL;
        return $this->executeQuery($query);
    }
}