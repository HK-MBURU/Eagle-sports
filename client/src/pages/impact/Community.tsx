import CategoryStoriesPage from "@/components/CategoryStoriesPage";

const Community = () => {
  return (
    <CategoryStoriesPage
      defaultCategory="Community Projects"
      title="Community Projects"
      description="Inspiring community initiatives and projects that are making a difference through athletics."
      showAllCategories={false}
    />
  );
};

export default Community;