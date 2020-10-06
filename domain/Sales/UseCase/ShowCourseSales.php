<?php

namespace Domain\Sales\UseCase;

use Domain\Courses\Exception\CourseNotFoundException;
use Domain\Courses\Gateway\CourseRepositoryInterface;
use Domain\Sales\Gateway\SaleRepositoryInterface;
use Domain\Sales\UseCase\RequestModel\ShowCourseSalesRequest;
use Domain\Sales\UseCase\ViewModel\ShowCourseSaleViewModel;

class ShowCourseSales
{
    protected SaleRepositoryInterface $saleRepository;
    protected CourseRepositoryInterface $courseRepository;

    public function __construct(SaleRepositoryInterface $saleRepository, CourseRepositoryInterface $courseRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->courseRepository = $courseRepository;
    }

    public function execute(ShowCourseSalesRequest $request): ShowCourseSaleViewModel
    {
        $course = $this->courseRepository->findOneOrNull($request->uuid);

        if (null === $course) {
            throw new CourseNotFoundException(sprintf(
                'Course with UUID %s was not found !',
                $request->uuid
            ));
        }

        $sales = $this->saleRepository->findSalesForCourse($course);

        return new ShowCourseSaleViewModel($course, $sales);
    }
}
