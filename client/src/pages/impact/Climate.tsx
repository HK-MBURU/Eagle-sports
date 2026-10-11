import CategoryStoriesPage from "@/components/CategoryStoriesPage";

const Climate = () => {
  return (
    <CategoryStoriesPage
      defaultCategory="Climate and athletics"
      title="Climate & Athletics"
      description="How athletes and the athletics community are addressing climate change and environmental challenges."
      showAllCategories={false}
    />
  );
};

export default Climate;